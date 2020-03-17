<?php

declare(strict_types=1);

// src/Controller/UserPrivacyController.php
namespace App\Controller;

class UserPrivacyController
{
    public function userPrivacyAction(): array
    {
        return array(
            'layout' => 'src/Layout/main/main.php',
            'content' => 'src/View/user-privacy/user-privacy.php',
            'activeMenu' => 'user-privacy',
            'title' => 'Prywatność'
        );
    }
}
