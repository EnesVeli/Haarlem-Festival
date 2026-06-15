<?php

namespace App\Services\Jazz;

use App\Framework\Session;
use App\Interfaces\Repositories\IJazzRepository;
use App\Interfaces\Services\IJazzCmsService;
use App\Repositories\JazzRepository;
use RuntimeException;

class JazzCmsService implements IJazzCmsService
{
    private static ?JazzCmsService $_instance = null;

    public static function getInstance() : JazzCmsService {
        if(self::$_instance === null) self::$_instance = new JazzCmsService(JazzRepository::getInstance());

        return self::$_instance;
    }

    private IJazzRepository $jazzRepo;

    private function __construct(IJazzRepository $jazzRepo)
    {
        $this->jazzRepo = $jazzRepo;
    }

    // dashboard

    public function getDashboardData(): array
    {
        return [
            'user' => Session::currentUser(),
        ];
    }

    // hero

    public function getHeroPageData(): array
    {
        return [
            'user' => Session::currentUser(),
            'hero' => $this->jazzRepo->getHero(),
        ];
    }

    public function updateHero(array $data, ?array $file): void
    {
        try {
            if ($file && !empty($file['tmp_name'])) {
                $data['image_path'] = $this->uploadImage($file, '/assets/uploads/jazz/hero/');
            }

            $this->jazzRepo->updateHero($data);
        } catch (\Exception $error) {
            throw new RuntimeException('Could not update hero.', 0, $error);
        }
    }

    // intro

    public function getIntroPageData(): array
    {
        return [
            'user' => Session::currentUser(),
            'intro' => $this->jazzRepo->getIntro(),
        ];
    }

    public function updateIntro(array $data): void
    {
        try {
            $this->jazzRepo->updateIntro($data);
        } catch (\Exception $error) {
            throw new RuntimeException('Could not update intro.', 0, $error);
        }
    }

    // experiences

    public function getExperiencesPageData(): array
    {
        return [
            'user' => Session::currentUser(),
            'experiences' => $this->jazzRepo->getExperiences(),
        ];
    }

    public function getExperienceByIdData(int $id): array
    {
        return [
            'user' => Session::currentUser(),
            'experience' => $this->jazzRepo->getExperienceById($id),
        ];
    }

    public function storeExperience(array $data, ?array $file): void
    {
        try {
            if ($file && !empty($file['tmp_name'])) {
                $data['image_path'] = $this->uploadImage($file, '/assets/uploads/jazz/experiences/');
            }

            $this->jazzRepo->storeExperience($data);
        } catch (\Exception $error) {
            throw new RuntimeException('Could not save experience.', 0, $error);
        }
    }

    public function updateExperience(array $data, ?array $file): void
    {
        try {
            if ($file && !empty($file['tmp_name'])) {
                $data['image_path'] = $this->uploadImage($file, '/assets/uploads/jazz/experiences/');
            }

            $this->jazzRepo->updateExperience($data);
        } catch (\Exception $error) {
            throw new RuntimeException('Could not update experience.', 0, $error);
        }
    }

    public function deleteExperience(int $id): void
    {
        $this->jazzRepo->deleteExperience($id);
    }

    // performers

    public function getPerformersPageData(): array
    {
        return [
            'user' => Session::currentUser(),
            'performers' => $this->jazzRepo->getAllPerformers(false),
        ];
    }

    public function getPerformerByIdData(int $id): array
    {
        return [
            'user' => Session::currentUser(),
            'performer' => $this->jazzRepo->getPerformerById($id),
            'highlights' => $this->jazzRepo->getHighlightsByPerformer($id),
            'tracks' => $this->jazzRepo->getTracksByPerformer($id),
        ];
    }

    public function storePerformer(array $data, ?array $heroFile, ?array $imageFile): void
    {
        try {
            if ($heroFile && !empty($heroFile['tmp_name'])) {
                $data['hero_image_path'] = $this->uploadImage($heroFile, '/assets/uploads/jazz/performers/');
            }

            if ($imageFile && !empty($imageFile['tmp_name'])) {
                $data['image_path'] = $this->uploadImage($imageFile, '/assets/uploads/jazz/performers/');
            }

            $this->jazzRepo->storePerformer($data);
        } catch (\Exception $error) {
            throw new RuntimeException('Could not save performer.', 0, $error);
        }
    }

    public function updatePerformer(array $data, ?array $heroFile, ?array $imageFile): void
    {
        try {
            if ($heroFile && !empty($heroFile['tmp_name'])) {
                $data['hero_image_path'] = $this->uploadImage($heroFile, '/assets/uploads/jazz/performers/');
            }
    
            if ($imageFile && !empty($imageFile['tmp_name'])) {
                $data['image_path'] = $this->uploadImage($imageFile, '/assets/uploads/jazz/performers/');
            }
    
            $this->jazzRepo->updatePerformer($data);
    
            $performerId = (int)($data['id'] ?? 0);
    
            if ($performerId > 0) {
                $this->jazzRepo->updateHighlightsByPerformer(
                    $performerId,
                    $data['highlights'] ?? []
                );
    
                $this->jazzRepo->updateTracksByPerformer(
                    $performerId,
                    $data['tracks'] ?? []
                );
            }
        } catch (\Exception $error) {
            throw new RuntimeException('Could not update performer.', 0, $error);
        }
    }

    public function deletePerformer(int $id): void
    {
        $this->jazzRepo->deletePerformer($id);
    }

    // recommendations

    public function getRecommendationsPageData(): array
    {
        return [
            'user' => Session::currentUser(),
            'recommendations' => $this->jazzRepo->getRecommendations(),
        ];
    }

    public function getRecommendationByIdData(int $id): array
    {
        return [
            'user' => Session::currentUser(),
            'recommendation' => $this->jazzRepo->getRecommendationById($id),
        ];
    }

    public function storeRecommendation(array $data, ?array $file): void
    {
        try {
            if ($file && !empty($file['tmp_name'])) {
                $data['image_path'] = $this->uploadImage($file, '/assets/uploads/jazz/recommendations/');
            }

            $this->jazzRepo->storeRecommendation($data);
        } catch (\Exception $error) {
            throw new RuntimeException('Could not save recommendation.', 0, $error);
        }
    }

    public function updateRecommendation(array $data, ?array $file): void
    {
        try {
            if ($file && !empty($file['tmp_name'])) {
                $data['image_path'] = $this->uploadImage($file, '/assets/uploads/jazz/recommendations/');
            }

            $this->jazzRepo->updateRecommendation($data);
        } catch (\Exception $error) {
            throw new RuntimeException('Could not update recommendation.', 0, $error);
        }
    }

    public function deleteRecommendation(int $id): void
    {
        $this->jazzRepo->deleteRecommendation($id);
    }

    // locations

    public function getLocationsPageData(): array
    {
        return [
            'user' => Session::currentUser(),
            'locations' => $this->jazzRepo->getLocations(),
        ];
    }

    public function getLocationByIdData(int $id): array
    {
        return [
            'user' => Session::currentUser(),
            'location' => $this->jazzRepo->getLocationById($id),
        ];
    }

    public function storeLocation(array $data): void
    {
        try {
            $this->jazzRepo->storeLocation($data);
        } catch (\Exception $error) {
            throw new RuntimeException('Could not save location.', 0, $error);
        }
    }

    public function updateLocation(array $data): void
    {
        try {
            $this->jazzRepo->updateLocation($data);
        } catch (\Exception $error) {
            throw new RuntimeException('Could not update location.', 0, $error);
        }
    }

    public function deleteLocation(int $id): void
    {
        $this->jazzRepo->deleteLocation($id);
    }

    // helper

    private function uploadImage(array $file, string $folder): string
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('Upload failed.');
        }

        if (($file['size'] ?? 0) > 5 * 1024 * 1024) {
            throw new \RuntimeException('File too large (max 5 MB).');
        }

        $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
        $allowedMime = ['image/jpeg', 'image/png', 'image/webp'];

        $ext = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExt, true)) {
            throw new \RuntimeException('Only jpg, jpeg, png, webp are allowed.');
        }

        $mime = mime_content_type($file['tmp_name']);
        if (!in_array($mime, $allowedMime, true)) {
            throw new \RuntimeException('Invalid image file.');
        }

        $uploadRoot = dirname(__DIR__, 3) . '/public';

        $filename = bin2hex(random_bytes(8)) . '.' . $ext;
        $path = $folder . $filename;
        $fullPath = $uploadRoot . $path;

        if (!is_dir(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }

        if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
            throw new \RuntimeException('Could not save uploaded file.');
        }

        return $path;
    }
}