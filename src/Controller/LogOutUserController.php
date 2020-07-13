<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\{Config, Controller};

class LogOutUserController extends Controller
{
    public function logOutUserAction(): array
    {
        $config = new Config();

        session_destroy();
        setcookie('cookie_login', '', 0, '/', $config->getServerName());

        return $this->redirectToRoute('login_page');
    }
}
