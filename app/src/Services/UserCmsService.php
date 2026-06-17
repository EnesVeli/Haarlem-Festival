<?php
namespace App\Services;

use App\Enums\UserRole;
use App\Models\Exceptions\DBDataFetchException;
use App\Models\Exceptions\DBDataNotFoundException;
use App\Models\Exceptions\EmailAlreadyRegisteredException;
use App\Models\Exceptions\EmptyFieldException;
use App\Models\Exceptions\QueryExecutionException;
use App\Models\User;
use App\Repositories\UserRepository;
use Exception;
use InvalidArgumentException;
use RoundingMode;
use Safe\Exceptions\FilesystemException;

class UserCmsService
{
    private static int $USERS_PER_PAGE = 24;

    private static ?UserCmsService $_instance = null;

    public static function getInstance() : UserCmsService {
        if(self::$_instance === null) self::$_instance = new UserCmsService(UserRepository::getInstance(), VerificationService::getInstance());

        return self::$_instance;
    }

    private UserRepository $userRepository;
    private VerificationService $verification_service;

    private function __construct(UserRepository $userRepository, VerificationService $verification_service)
    {
        $this->userRepository = $userRepository;
        $this->verification_service = $verification_service;
    }

    public function getUsersList(int $sort, int $order, int $page) : ?array {
        if($sort < 0 || $sort > 4) throw new InvalidArgumentException('Invalid sorting value.');

        if($page < 0) throw new InvalidArgumentException('Invalid page number.');
        
        return $this->userRepository->getUserListCms($sort, $order, $page, self::$USERS_PER_PAGE);
    }

    public function countUsers() : int {
        $count = $this->userRepository->countAllUsers();

        if($count === false) throw new DBDataFetchException('Failed to get user count for cms.');

        return $count;
    }

    public function calcPagination(int $page, int $user_total_count) : array {
        $page_count = round($user_total_count / self::$USERS_PER_PAGE, 0, RoundingMode::AwayFromZero);

        // Calculate offset and limit for page number selection
        $offset = 0; // Left offset of pages button
        $limit = 0; // Right offset of pages button

        if($page < abs($page - $page_count + 1)){ // If current page is closer to first page than last, start from offset
            for (; $offset < 3; $offset++) { 
                if($page - $offset <= 0) break;
            }

            for (; $limit < 7 - $offset; $limit++) { 
                if($page + $limit >= $page_count) break;
            }
        }  
        else{ // Otherwise from limit
            for (; $limit < 4; $limit++) { 
                if($page + $limit >= $page_count) break;
            }

            for (; $offset < 7 - $limit; $offset++) { 
                if($page - $offset <= 0) break;
            }                       
        } 

        $out = [];

        $out['offset'] = $offset;
        $out['limit'] = $limit;
        $out['page_count'] = $page_count;

        return $out;
    }

    public function getByUserId(int $user_id) : ?User
    {
        return $this->userRepository->findById($user_id);
    }

    
    public function getByEmail(string $email) : ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    public function editUser(User $edit, mixed $profile_pic) {
        // Get user
        $user = $this->userRepository->findById($edit->user_id);
        if($user === null) throw new DBDataNotFoundException('Failed to find user to edit.');

        // Check email
        if($user->email !== $edit->email){
            // Check if email is ok
            $this->verification_service->verifyEmail($edit->email);

            // Check if email is in use
            $m_u = $this->userRepository->findByEmail($edit->email);

            if($m_u !== null) throw new EmailAlreadyRegisteredException();
        }

        // Check password
        if($edit->password !== '' && !password_verify($edit->password, $user->password)){
            $this->verification_service->verifyPassword($edit->password, $edit->password);
        }

        // Edit user
        if($user->name !== $edit->name) {
            if(!$this->userRepository->updateName($edit->user_id, $edit->name)) throw new QueryExecutionException('Failed to edit users name.');
        }

        if($user->email !== $edit->email) {
            if(!$this->userRepository->updateEmail($edit->user_id, $edit->email)) throw new QueryExecutionException('Failed to edit users email.');
        }

        if($edit->password !== '' && !password_verify($edit->password, $user->password)) {
            if(!$this->userRepository->updatePassword($edit->user_id, password_hash($edit->password, PASSWORD_DEFAULT))) throw new QueryExecutionException('Failed to edit users password.');
        }

        if($user->active !== $edit->active) {
            if(!$this->userRepository->updateActive($edit->user_id, $edit->active)) throw new QueryExecutionException('Failed to edit users active status.');
        }

        if($user->role !== $edit->role) {
            if(!$this->userRepository->updateRole($edit->user_id, $edit->role)) throw new QueryExecutionException('Failed to edit users role.');
        }

        if($profile_pic !== null) {
            $file_name = $this->addImageToDir('', $edit->user_id , $profile_pic['name'], $profile_pic['tmp_name']);

            if($file_name === null) throw new FilesystemException('Failed to add profile picture to uploads.');

            if(!$this->userRepository->updateProfilePictureUrl($edit->user_id, '/assets/uploads/' . $file_name)) throw new QueryExecutionException('Failed to edit users profile picture.');

            $this->deleteImageFromDir($user->profile_picture_url);
        }
    }

    public function addUser(User $add, mixed $profile_pic) {
        // Check email
        $this->verification_service->verifyEmail($add->email);

        $m_u = $this->getByEmail($add->email);

        if($m_u !== null) throw new EmailAlreadyRegisteredException();
        
        // Check password
        $this->verification_service->verifyPassword($add->password, $add->password);
        $add->password = password_hash($add->password, PASSWORD_DEFAULT);

        // Add user
        $new_user_id = $this->userRepository->create($add);
        if($new_user_id === false) throw new QueryExecutionException('Failed to create user.');

        // Add profile picture
        if($profile_pic !== null) {
            $file_name = $this->addImageToDir('', $new_user_id , $profile_pic['name'], $profile_pic['tmp_name']);

            if($file_name === null) throw new FilesystemException('Failed to add profile picture to uploads.');

            if(!$this->userRepository->updateProfilePictureUrl($new_user_id, '/assets/uploads/' . $file_name)) throw new QueryExecutionException('Failed to edit users profile picture.');
        }
    }

    /**
     * Moves file from uploads to specified directory in uploads folder.
     * @param string $end_dir relative to uploads folder path do directory (e.g. 'yummy/topper/'), path must end with '/'.
     * @param int $user_id Id of the user.
     * @param mixed $origin_name name of origin file.
     * @param mixed $tmp_name tmp name of uploded file.
     * @return ?string on success returns new file name with extention. On fail returns null.
     */
    private function addImageToDir(string $end_dir, int $user_id , $origin_name, $tmp_name) : ?string {
        if($tmp_name == null) return null;

        // Crafting path
        $file_name = 'user_' . $user_id . '_' . bin2hex(openssl_random_pseudo_bytes(16)) . '.' . pathinfo($origin_name, PATHINFO_EXTENSION);
        $path = __DIR__ . '/../../public/assets/uploads/' . $end_dir . $file_name;

        if(move_uploaded_file($tmp_name, $path)) return $file_name;
        
        return null;
    }

    /**
     * Deletes file from specified directory in uploads folder.
     * @param mixed $file_path name of origin file.
     * @return bool true on success, false on failure.
     */
    private function deleteImageFromDir($file_path) : bool {
        if($file_path == null) return false;

        // Crafting path
        $path = __DIR__ . '/../../public' . $file_path;

        if(!file_exists($path)) return false;

        return unlink($path);
    }
}