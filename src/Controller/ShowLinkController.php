<?php

declare(strict_types=1);

namespace App\Controller;

class ShowLinkController
{
    public function showLinkAction(array $request, array $session): array
    {
        return array(
            'layout' => 'src/Layout/show-site/main.php',
            'content' => 'src/View/show-site/show-site.php',
            'activeMenu' => 'show-link',
            'title' => 'Podgląd strony',
            'www' => $request['www']
        );
    }
}
