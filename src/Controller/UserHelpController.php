<?php

declare(strict_types=1);

// src/Controller/UserHelpController.php
namespace App\Controller;

class UserHelpController
{
    public function userHelpAction(): array
    {
        return array(
            'layout' => 'src/Layout/main/main.php',
            'content' => 'src/View/user-help/user-help.php',
            'activeMenu' => 'user-help',
            'title' => 'Pomoc'
        );
    }
}
