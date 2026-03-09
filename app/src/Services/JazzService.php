<?php

namespace App\Services;

use App\Repositories\CmsContentRepository;
use App\Repositories\JazzRepository;

class JazzService
{
    private CmsContentRepository $cmsRepo;
    private JazzRepository $jazzRepo;

    public function __construct()
    {
        $this->cmsRepo = new CmsContentRepository();
        $this->jazzRepo = new JazzRepository();
    }

    public function getHomePageData(): array
    {
        return [
            'experiences' => $this->cmsRepo->getBlocks('jazz_home', 'experience'),
            'performers' => $this->cmsRepo->getBlocks('jazz_home', 'performer'),
            'recommendations' => $this->cmsRepo->getBlocks('jazz_home', 'recommendation'),
        ];
    }

    public function getPerformerById(int $id): ?array
    {
        return $this->jazzRepo->getPerformerById($id);
    }
}