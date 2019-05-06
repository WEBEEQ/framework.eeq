<?php declare(strict_types=1);

// src/Core/Login.php
namespace App\Core;

use App\Core\Param;

class Login extends DataBase
{
    public function cookieLogIn(): void
    {
        $login = explode(';', Param::prepareString($_COOKIE['login']));

        $this->dbConnect();

        $userPasswordCode = $this->isUserPasswordCode(
            $login[0],
            $login[1],
            $id,
            $admin,
            $active
        );
        if ($userPasswordCode) {
            if ($active) {
                $this->dbQuery(
                    "UPDATE `users`
                    SET `users`.`user_ip_loged` = '" . $_SERVER['REMOTE_ADDR']
                        . "', `users`.`user_date_loged` = '"
                        . date('Y-m-d H:i:s') . "'
                    WHERE `users`.`user_id` = " . $id
                );
                $_SESSION['id'] = $id;
                $_SESSION['admin'] = $admin;
                $_SESSION['user'] = $login[0];
            }
        }

        $this->dbClose();
    }

    private function isUserPasswordCode(
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
