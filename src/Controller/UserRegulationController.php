<?php declare(strict_types=1);

// src/Controller/UserRegulationController.php
namespace App\Controller;

class UserRegulationController
{
    public function userRegulationAction(): array
    {
        return array(
            'layout' => 'src/Layout/main/main.php',
            'content' => 'src/View/user-regulation/user-regulation.php',
            'activeMenu' => 'user-regulation',
            'title' => 'Regulamin'
        );
    }
}
