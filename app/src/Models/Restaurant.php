<?php

namespace App\Models;

class Restaurant {
    public int $restaurant_id;
    public string $mini_img_path;
    public string $name;
    public string $mini_text;
    public float $rating;
    public int $cost_rating;
    public bool $active;

    /**
    * @return ?array if restaurant rating is null, returns null. Otherwise returns list of 5 elements each is 0 (empty star), 1 (half star) or 2 (whole star).
    */
    public function getStars() : ?array {
        if($this->rating == null) return null;

        $r = round($this->rating * 2, 0, PHP_ROUND_HALF_DOWN);

        return [$r >= 2 ? 2 : $r,
        $r - 2 >= 2 ? 2 : ($r - 2 <= 0 ? 0 : 1),
        $r - 4 >= 2 ? 2 : ($r - 4 <= 0 ? 0 : 1),
        $r - 6 >= 2 ? 2 : ($r - 6 <= 0 ? 0 : 1),
        $r - 8 >= 2 ? 2 : ($r - 8 <= 0 ? 0 : 1)];
    }

    /**
    * @return string returns a string representing cost rating as string
    */
    public function getCostRatingString() : string {
        if($this->cost_rating >= 3) return '€€€';
        else if($this->cost_rating == 2) return '€€';
        else return '€';
    }

    public function getRatingFormated() : ?string {
        if($this->rating == null) return null;

        return number_format((float)round($this->rating, 1, PHP_ROUND_HALF_UP), 1, '.', '');
    }
}