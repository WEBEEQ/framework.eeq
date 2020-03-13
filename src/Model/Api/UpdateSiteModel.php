<?php declare(strict_types=1);

// src/Model/Api/UpdateSiteModel.php
namespace App\Model\Api;

use App\Core\DataBase;

class UpdateSiteModel extends DataBase
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

    public function isUserSite(int $id, int $site): bool
    {
        $result = $this->dbQuery(
            'SELECT `sites`.`site_id` FROM `sites`
            INNER JOIN `users` ON `sites`.`user_id` = `users`.`user_id`
            WHERE `users`.`user_active` = 1 AND `sites`.`user_id` = ' . $id . '
                AND `sites`.`site_id` = ' . $site
        );

        return ($this->dbFetchArray($result)) ? true : false;
    }

    public function setSiteData(
        int $site,
        int $visible,
        string $name,
        string $ip,
        string $date
    ): bool {
        return $this->dbQuery(
            'UPDATE `sites`
            SET `sites`.`site_visible` = ' . $visible . ",
                `sites`.`site_name` = '" . $name . "',
                `sites`.`site_ip_updated` = '" . $ip . "',
                `sites`.`site_date_updated` = '" . $date . "'
            WHERE `sites`.`site_id` = " . $site
        );
    }
}
