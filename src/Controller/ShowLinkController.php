<?php declare(strict_types=1);

// src/Controller/ShowLinkController.php
namespace App\Controller;

class ShowLinkController
{
    public function showLinkAction(string $www): array
    {
        return array(
            'layout' => 'src/Layout/show-site/main.php',
            'content' => 'src/View/show-site/show-site.php',
            'activeMenu' => 'show-link',
            'title' => 'Podgląd strony',
            'www' => $www
        );
    }
}
