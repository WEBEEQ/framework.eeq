<?php

declare(strict_types=1);

namespace App\Controller;

class ShowInfoController
{
    public function showInfoAction(): array
    {
        return array(
            'layout' => 'layout/show-info/main.php',
            'content' => 'show-info/show-info.php',
            'activeMenu' => 'show-info',
            'title' => 'Informacja'
        );
    }
}
