<?php
namespace App\ViewModels;

class StoriesViewModel {
    public array $program = []; 
    public array $filterLanguages = []; 
    public array $filterTypes = []; 
    public array $filterAges = []; 
    public string $pageTitle = "Stories in Haarlem";
    public string $heroText = "During the last weekend of July, the streets of Haarlem transform into a living library. Stories in Haarlem brings a mix of live performances, intimate podcast recordings, and immersive family shows to unique locations across the city.";
    
    public string $errorMessage = '';
}