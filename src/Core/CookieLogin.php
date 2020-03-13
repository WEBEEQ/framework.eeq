<?php declare(strict_types=1);

// src/Core/CookieLogin.php
namespace App\Core;

class CookieLogin extends DataBase
{
    protected $config;
    protected $param;

    public function __construct(object $config, object $param)
    {
        parent::__construct();
        $this->config = $config;
        $this->param = $param;
    }

    public function setCookieLogin(): void
    {
        if (!$_SESSION['user'] && $_COOKIE['login']) {
            $this->setSessionLogin();
        }
    }

    private function setSessionLogin(): void
    {
        $login = explode(';', $this->param->prepareString($_COOKIE['login']));

        $this->dbConnect();

        $userPassword = $this->isUserPassword(
            $login[0],
            $login[1],
            $id,
            $admin,
            $active
        );
        if ($userPassword) {
            if ($active) {
                $userLoged = $this->dbQuery(
                    "UPDATE `users`
                    SET `users`.`user_ip_loged` = '"
                        . $this->config->getRemoteAddress()
                        . "', `users`.`user_date_loged` = '"
                        . $this->config->getDateTimeNow() . "'
                    WHERE `users`.`user_id` = " . $id
                );
                if ($userLoged) {
                    $_SESSION['id'] = $id;
                    $_SESSION['admin'] = $admin;
                    $_SESSION['user'] = $login[0];
                }
            }
        }

        $this->dbClose();
    }

    private function isUserPassword(
        string $login,
        string $password,
        ?int &$user_id,
        ?bool &$user_admin,
        ?bool &$user_active
    ): bool {
        $result = $this->dbQuery(
            "SELECT `users`.`user_id`, `users`.`user_admin`,
                `users`.`user_active` FROM `users`
            WHERE `users`.`user_login` = '" . $login . "'
                AND `users`.`user_password` = '" . $password . "'"
        );
        if ($row = $this->dbFetchArray($result)) {
            extract($row);

            return true;
        }

        return false;
    }
}
