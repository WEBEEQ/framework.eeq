<?php

declare(strict_types=1);

namespace App\Model;

use App\Core\DataBase;

class ChangePasswordModel extends DataBase
{
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
                . password_hash($password, PASSWORD_DEFAULT) . "',
                `users`.`user_key` = '" . $key . "',
                `users`.`user_ip_updated` = '" . $ip . "',
                `users`.`user_date_updated` = '" . $date . "'
            WHERE `users`.`user_id` = " . $id
        );
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
}
