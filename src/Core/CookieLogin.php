<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Controller;

class CookieLogin extends Controller
{
    protected object $config;

    public function __construct(object $config)
    {
        $this->config = $config;
    }

    public function setCookieLogin(): void
    {
        if (!$_SESSION['user'] && $_COOKIE['cookie_login']) {
            $this->setManager();
            $this->setSessionLogin();
        }
    }

    private function setSessionLogin(): void
    {
        $cookie = explode(';', $_COOKIE['cookie_login']);

        $userData = $this->getUserData($cookie[0], $cookie[1]);
        if ($userData['user_active']) {
            $query = $this->manager->createQuery(
                "UPDATE `users` u
                SET u.`user_ip_loged` = ':ip', u.`user_date_loged` = ':date'
                WHERE u.`user_id` = :user"
            )
                ->setParameter('ip', $this->config->getRemoteAddress())
                ->setParameter('date', $this->config->getDateTimeNow())
                ->setParameter('user', $userData['user_id'])
                ->getStrQuery();
            $userLoged = $this->database->dbQuery($query);
            if ($userLoged) {
                $_SESSION['id'] = $userData['user_id'];
                $_SESSION['admin'] = $userData['user_admin'];
                $_SESSION['user'] = $cookie[0];
            }
        }
    }

    private function getUserData(string $login, string $password): array
    {
        $arrayResult = array();

        $query = $this->manager->createQuery(
            "SELECT u.`user_id`, u.`user_admin`, u.`user_active` FROM `users` u
            WHERE u.`user_login` = ':login'
                AND u.`user_password` = ':password'"
        )
            ->setParameter('login', $login)
            ->setParameter('password', $password)
            ->getStrQuery();
        $result = $this->database->dbQuery($query);
        if ($row = $this->database->dbFetchArray($result)) {
            $arrayResult['user_id'] = (int) $row['user_id'];
            $arrayResult['user_admin'] = (int) $row['user_admin'];
            $arrayResult['user_active'] = (int) $row['user_active'];
        }

        return $arrayResult;
    }
}
