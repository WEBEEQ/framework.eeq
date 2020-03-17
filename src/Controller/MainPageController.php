<?php

declare(strict_types=1);

// src/Controller/MainPageController.php
namespace App\Controller;

class MainPageController
{
    public function mainPageAction(): array
    {
        return array(
            'layout' => 'src/Layout/main/main.php',
            'content' => 'src/View/main-page/main-page.php',
            'activeMenu' => 'main-page',
            'title' => 'SIECIQ - Sieć reklamowa dla Państwa stron'
        );
    }
}
