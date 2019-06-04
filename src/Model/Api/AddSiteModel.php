<?php declare(strict_types=1);

// src/Model/Api/AddSiteModel.php
namespace App\Model\Api;

use App\Core\DataBase;

class AddSiteModel extends DataBase
{
    public function getUserPassword(
        string $login,
        ?int &$user_id
    ): ?string {
        $result = $this->dbQuery(
            "SELECT `users`.`user_id`, `users`.`user_password` FROM `users`
            WHERE `users`.`user_active` = 1
                AND `users`.`user_login` = '" . $login . "'"
        );
        if ($row = $this->dbFetchArray($result)) {
            extract($row);

            return $user_password;
        }

        return null;
    }

    public function addSiteData(
        int $id,
        string $name,
        string $www,
        string $ip,
        string $date
    ): bool {
        return $this->dbQuery(
            'INSERT INTO `sites` (
                `user_id`,
                `site_active`,
                `site_visible`,
                `site_name`,
                `site_url`,
                `site_ip_added`,
                `site_date_added`
            )
            VALUES (
                ' . $id . ",
                0,
                1,
                '" . $name . "',
                '" . $www . "',
                '" . $ip . "',
                '" . $date . "'
            )"
        );
    }
}
