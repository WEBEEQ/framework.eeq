<?php declare(strict_types=1);

// src/Model/RestModel.php
namespace App\Model;

use App\Core\DataBase;

class RestModel extends DataBase
{
    public function isLoginPassword(
        string $login,
        string $password,
        ?int &$user_id
    ): bool {
        $result = $this->dbQuery(
            "SELECT `users`.`user_id` FROM `users`
            WHERE `users`.`user_active` = 1
                AND `users`.`user_login` = '" . $login . "'
                AND `users`.`user_password` = '" . $password . "'"
        );
        if ($row = $this->dbFetchArray($result)) {
            extract($row);

            return true;
        }

        return false;
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

    public function deleteSiteData(int $site): bool
    {
        return $this->dbQuery(
            'DELETE FROM `sites`
            WHERE `sites`.`site_id` = ' . $site
        );
    }
}
