<?php
namespace App\Services;

use App\Enums\UserRole;
use App\Models\Exceptions\DBDataFetchException;
use App\Models\Exceptions\EmailAlreadyRegisteredException;
use App\Models\Exceptions\EmptyFieldException;
use App\Models\User;
use App\Repositories\UserRepository;
use Exception;
use InvalidArgumentException;
use RoundingMode;

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

        $users = $this->userRepository->getUserListCms($sort, $order, $page, self::$USERS_PER_PAGE);

        if($users === false) throw new DBDataFetchException("Failed to get users for cms.");

        if($users === null) return null;

        return $users;
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
        $user = $this->userRepository->findById($user_id);

        if ($user === false) throw new DBDataFetchException("Failed to get user by id.");

        return $user;
    }
}