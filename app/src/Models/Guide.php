<?php

namespace App\Models;

class Guide {
    public int $guide_id;
    public string $mini_img_path;
    public string $mini_title;
    public string $mini_text;
    public bool $active;
}