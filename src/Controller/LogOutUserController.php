<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Config;

class LogOutUserController
{
    public function logOutUserAction(): array
    {
        $config = new Config();

        session_destroy();
        setcookie('cookie_login', '', 0, '/', $config->getServerName());
        header('Location: ' . $config->getUrl() . '/logowanie');
    }
}
