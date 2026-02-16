<?php

namespace App\Controllers;

use App\Services\HistoryService;

class HistoryController
{
    private HistoryService $service;

    public function __construct()
    {
        $this->service = new HistoryService();
    }

    /**
     * Display the main history page
     */
    public function index()
    {
        $highlights = $this->service->getHighlights();
        $tickets = $this->service->getTickets();
        $content = $this->service->getContent();

        require __DIR__ . '/../Views/history/index.php';
    }

    /**
     * Display a detail page for a specific highlight
     * 
     * @param string $slug The URL slug for the highlight
     */
    public function detail($vars)
    {
    // Extract slug from the vars array
    $slug = $vars['slug'] ?? '';
    
    $pageData = $this->service->getDetailPage($slug);
    
    if (!$pageData) {
        // Redirect to 404 or history page if not found
        header('Location: /history');
        exit;
    }

    $detail = $pageData['detail'];
    $sections = $pageData['sections'];
    $gallery = $pageData['gallery'];
    $facts = $pageData['facts'];
    
    // Get other highlights for "Complete Your Journey" section
    $otherHighlights = $this->service->getOtherHighlights($slug);

    require __DIR__ . '/../Views/history/detail.php';
    }
}