<?php

declare(strict_types=1);

// src/Model/LogInUserModel.php
namespace App\Model;

use App\Core\DataBase;

class LogInUserModel extends DataBase
{
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

    public function setUserLoged(int $id, string $ip, string $date): bool
    {
        return $this->dbQuery(
            "UPDATE `users` SET `users`.`user_ip_loged` = '" . $ip . "',
                `users`.`user_date_loged` = '" . $date . "'
            WHERE `users`.`user_id` = " . $id
        );
    }
}
