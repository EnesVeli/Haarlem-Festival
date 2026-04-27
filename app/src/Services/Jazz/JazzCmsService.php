<?php

namespace App\Services\Jazz;

use App\Framework\Session;
use App\Repositories\JazzRepository;

class JazzCmsService
{
    private static ?JazzCmsService $_instance = null;

    public static function getInstance() : JazzCmsService {
        if(self::$_instance === null) self::$_instance = new JazzCmsService(JazzRepository::getInstance());

        return self::$_instance;
    }

    private JazzRepository $jazzRepo;

    private function __construct(JazzRepository $jazzRepo)
    {
        $this->jazzRepo = $jazzRepo;
    }

    // dashboard

    public function getDashboardData(): array
    {
        return [
            'user' => Session::user(),
        ];
    }

    // hero

    public function getHeroPageData(): array
    {
        return [
            'user' => Session::user(),
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
            die('Could not update hero.');
        }
    }

    // intro

    public function getIntroPageData(): array
    {
        return [
            'user' => Session::user(),
            'intro' => $this->jazzRepo->getIntro(),
        ];
    }

    public function updateIntro(array $data): void
    {
        try {
            $this->jazzRepo->updateIntro($data);
        } catch (\Exception $error) {
            die('Could not update intro.');
        }
    }

    // experiences

    public function getExperiencesPageData(): array
    {
        return [
            'user' => Session::user(),
            'experiences' => $this->jazzRepo->getExperiences(),
        ];
    }

    public function getExperienceByIdData(int $id): array
    {
        return [
            'user' => Session::user(),
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
            die('Could not save experience.');
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
            die('Could not update experience.');
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
            'user' => Session::user(),
            'performers' => $this->jazzRepo->getAllPerformers(false),
        ];
    }

    public function getPerformerByIdData(int $id): array
    {
        return [
            'user' => Session::user(),
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
            die('Could not save performer.');
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
            die('Could not update performer.');
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
            'user' => Session::user(),
            'recommendations' => $this->jazzRepo->getRecommendations(),
        ];
    }

    public function getRecommendationByIdData(int $id): array
    {
        return [
            'user' => Session::user(),
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
            die('Could not save recommendation.');
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
            die('Could not update recommendation.');
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
            'user' => Session::user(),
            'locations' => $this->jazzRepo->getLocations(),
        ];
    }

    public function getLocationByIdData(int $id): array
    {
        return [
            'user' => Session::user(),
'location' => $this->jazzRepo->getLocationById($id),        ];
    }

    public function storeLocation(array $data): void
    {
        try {
            $this->jazzRepo->storeLocation($data);
        } catch (\Exception $error) {
            die('Could not save location.');
        }
    }

    public function updateLocation(array $data): void
    {
        try {
            $this->jazzRepo->updateLocation($data);
        } catch (\Exception $error) {
            die('Could not update location.');
        }
    }

    public function deleteLocation(int $id): void
    {
        $this->jazzRepo->deleteLocation($id);
    }

    // helper

    private function uploadImage(array $file, string $folder): string
    {
        $uploadRoot = dirname(__DIR__, 3) . '/public';

        $filename = time() . '_' . basename($file['name']);
        $path = $folder . $filename;
        $fullPath = $uploadRoot . $path;

        if (!is_dir(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0777, true);
        }

        move_uploaded_file($file['tmp_name'], $fullPath);

        return $path;
    }
}