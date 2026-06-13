<?php

namespace App\ViewModels;

use App\Models\HomeEvent;

class HomeViewModel
{
    public string $pageTitle;
    public string $heroImage;
    public string $heroTitle;
    public string $heroSubtitle;
    public string $heroDescription;
    public string $programTitle;
    public string $programDescription;

    /** @var HomeEvent[] */
    public array $eventCards;

    public array $venueList;

    /**
     * @param array<string, string> $homeContent
     * @param HomeEvent[]            $eventCards
     */
    public function __construct(array $homeContent, array $eventCards, array $venueList)
    {
        $this->pageTitle          = 'Home - The Festival Haarlem';
        $this->heroImage          = $homeContent['hero_image']          ?? 'Heroimage.png';
        $this->heroTitle          = $homeContent['hero_title']          ?? 'THE FESTIVAL';
        $this->heroSubtitle       = $homeContent['hero_subtitle']       ?? '5 Events • 4 Days • One Haarlem';
        $this->heroDescription    = $homeContent['hero_description']    ?? 'Discover the vibrant heart of Haarlem this July during The Festival, a unique four day celebration that transforms our historic city into a stage for culture, music, and culinary excellence.';
        $this->programTitle       = $homeContent['program_title']       ?? 'What Is My Program?';
        $this->programDescription = $homeContent['program_description'] ?? '';
        $this->eventCards         = $eventCards;
        $this->venueList          = $venueList;
    }
}
