<?php declare(strict_types=1);

// src/Controller/LogoutUserController.php
namespace App\Controller;

use App\Core\Config;

class LogoutUserController
{
    public function logoutUserAction(): array
    {
        $config = new Config();
        session_destroy();
        setcookie('login', '', 0, '/');
        header('Location: ' . $config->getUrl() . '/logowanie');
    }
}
