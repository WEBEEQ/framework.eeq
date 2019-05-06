<?php declare(strict_types=1);

// src/Model/AddUserModel.php
namespace App\Model;

use App\Core\DataBase;

class AddUserModel extends DataBase
{
    public function generateKey(): string
    {
        $key = '';

        for ($i = 0; $i < 32; $i++) {
            if (rand(0, 2) != 0) {
                $j = rand(0, 25);
                $key .= substr('ABCDEFGHIJKLMNOPQRSTUVWXYZ', $j, 1);
            } else {
                $key .= rand(0, 9);
            }
        }

        return $key;
    }

    public function addUserData(
        string $name,
        string $surname,
        string $login,
        string $password,
        string $email,
        string $key,
        string $ip,
        string $date
    ): bool {
        return $this->dbQuery(
            "INSERT INTO `users` (
                `user_admin`,
                `user_active`,
                `user_name`,
                `user_surname`,
                `user_login`,
                `user_password`,
                `user_email`,
                `user_key`,
                `user_description`,
                `user_show`,
                `user_ip_added`,
                `user_date_added`
            )
            VALUES (
                0,
                0,
                '" . $name . "',
                '" . $surname . "',
                '" . $login . "',
                '" . md5($password) . "',
                '" . $email . "',
                '" . $key . "',
                '',
                0,
                '" . $ip . "',
                '" . $date . "'
            )"
        );
    }

    public function setUserActive(int $id): bool
    {
        return $this->dbQuery(
            'UPDATE `users` SET `users`.`user_active` = 1
            WHERE `users`.`user_id` = ' . $id
        );
    }

    public function isUserLogin(string $login): bool
    {
        $result = $this->dbQuery(
            "SELECT `users`.`user_id` FROM `users`
            WHERE `users`.`user_login` = '" . $login . "'"
        );

        return ($this->dbFetchArray($result)) ? true : false;
    }

    public function getUserKey(
        string $login,
        ?int &$user_id,
        ?bool &$user_active
    ): ?string {
        $result = $this->dbQuery(
            "SELECT `users`.`user_id`, `users`.`user_active`,
                `users`.`user_key` FROM `users`
            WHERE `users`.`user_login` = '" . $login . "'"
        );
        if ($row = $this->dbFetchArray($result)) {
            extract($row);

            return $user_key;
        }

        return null;
    }
}
