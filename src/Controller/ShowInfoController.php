<?php

declare(strict_types=1);

namespace App\Controller;

class ShowInfoController
{
    public function showInfoAction(): array
    {
        return array(
            'layout' => 'src/Layout/show-info/main.php',
            'content' => 'src/View/show-info/show-info.php',
            'activeMenu' => 'show-info',
            'title' => 'Informacja'
        );
    }
}
