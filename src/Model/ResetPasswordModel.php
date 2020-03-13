<?php declare(strict_types=1);

// src/Model/ResetPasswordModel.php
namespace App\Model;

use App\Core\DataBase;

class ResetPasswordModel extends DataBase
{
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
}
