<?php

declare(strict_types=1);

// src/Controller/LogOutUserController.php
namespace App\Controller;

use App\Core\Config;

class LogOutUserController
{
    public function logOutUserAction(): array
    {
        $config = new Config();

        session_destroy();
        setcookie('login', '', 0, '/');
        header('Location: ' . $config->getUrl() . '/logowanie');
    }
}
