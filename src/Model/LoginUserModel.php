<?php declare(strict_types=1);

// src/Model/LoginUserModel.php
namespace App\Model;

use App\Core\DataBase;

class LoginUserModel extends DataBase
{
    public function generatePassword(): string
    {
        $password = '';

        for ($i = 0; $i < 30; $i++) {
            if (rand(0, 2) != 0) {
                $j = rand(0, 51);
                $password .= substr(
                    'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz',
                    $j,
                    1
                );
            } else {
                $j = rand(0, 19);
                $password .= substr(
                    '1234567890!@#$%^&*()',
                    $j,
                    1
                );
            }
        }

        return $password;
    }

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

    public function isUserLogin(
        string $login,
        ?bool &$user_active,
        ?string &$user_email,
        ?string &$user_key
    ): bool {
        $result = $this->dbQuery(
            "SELECT `users`.`user_active`, `users`.`user_email`,
                `users`.`user_key` FROM `users`
            WHERE `users`.`user_login` = '" . $login . "'"
        );
        if ($row = $this->dbFetchArray($result)) {
            extract($row);

            return true;
        }

        return false;
    }

    public function setUserPassword(
        int $id,
        string $password,
        string $key,
        string $ip,
        string $date
    ): bool {
        return $this->dbQuery(
            "UPDATE `users`
            SET `users`.`user_password` = '"
                . password_hash($password, PASSWORD_ARGON2I) . "',
                `users`.`user_key` = '" . $key . "',
                `users`.`user_ip_updated` = '" . $ip . "',
                `users`.`user_date_updated` = '" . $date . "'
            WHERE `users`.`user_id` = " . $id
        );
    }

    public function getUserPassword(
        string $login,
        ?int &$user_id,
        ?bool &$user_admin,
        ?bool &$user_active,
        ?string &$user_email,
        ?string &$user_key
    ): ?string {
        $result = $this->dbQuery(
            "SELECT `users`.`user_id`, `users`.`user_admin`,
                `users`.`user_active`, `users`.`user_password`,
                `users`.`user_email`, `users`.`user_key` FROM `users`
            WHERE `users`.`user_login` = '" . $login . "'"
        );
        if ($row = $this->dbFetchArray($result)) {
            extract($row);

            return $user_password;
        }

        return null;
    }

    public function getUserKey(
        string $login,
        ?int &$user_id,
        ?bool &$user_active,
        ?string &$user_email
    ): ?string {
        $result = $this->dbQuery(
            "SELECT `users`.`user_id`, `users`.`user_active`,
                `users`.`user_email`, `users`.`user_key` FROM `users`
            WHERE `users`.`user_login` = '" . $login . "'"
        );
        if ($row = $this->dbFetchArray($result)) {
            extract($row);

            return $user_key;
        }

        return null;
    }

    public function setUserLoged(int $id, string $ip, string $date): bool
    {
        return $this->dbQuery(
            "UPDATE `users` SET `users`.`user_ip_loged` = '" . $ip . "',
                `users`.`user_date_loged` = '" . $date . "'
            WHERE `users`.`user_id` = " . $id
        );
    }
}
