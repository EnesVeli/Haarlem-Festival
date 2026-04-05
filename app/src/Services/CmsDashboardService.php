<?php

namespace App\Services;

use App\Framework\Session;

class CmsDashboardService
{
    public function getDashboardData(): array
    {
        return [
            'user' => Session::user(),
            'sections' => [
                [
                    'title' => 'Main',
                    'description' => 'Manage the homepage and general festival content.',
                    'url' => '/cms/home',
                ],
                [
                    'title' => 'History',
                    'description' => 'Manage history page content.',
                    'url' => '/cms/history',
                ],
                [
                    'title' => 'Stories',
                    'description' => 'Manage stories and story bookings.',
                    'url' => '/cms/stories',
                ],
                [
                    'title' => 'Yummy',
                    'description' => 'Manage food and restaurant content.',
                    'url' => '/cms/yummy',
                ],
                [
                    'title' => 'Jazz',
                    'description' => 'Manage jazz homepage and performer content.',
                    'url' => '/cms/jazz/home',
                ],
                [
                    'title' => 'Dance',
                    'description' => 'Manage dance content.',
                    'url' => '/cms/dance',
                ],
            ],
        ];
    }
}