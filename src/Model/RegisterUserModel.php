<?php declare(strict_types=1);

// src/Model/RegisterUserModel.php
namespace App\Model;

use App\Core\DataBase;

class RegisterUserModel extends DataBase
{
    public function generateKey(): string
    {
        $key = '';

        for ($i = 0; $i < 100; $i++) {
            if (rand(0, 2) != 0) {
                $j = rand(0, 51);
                $key .= substr(
                    'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz',
                    $j,
                    1
                );
            } else {
                $j = rand(0, 9);
                $key .= substr(
                    '1234567890',
                    $j,
                    1
                );
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
                '" . password_hash($password, PASSWORD_DEFAULT) . "',
                '" . $email . "',
                '" . $key . "',
                '',
                0,
                '" . $ip . "',
                '" . $date . "'
            )"
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
}
