<?php

declare(strict_types=1);

namespace App\Model;

use App\Core\DataBase;

class AcceptSiteModel extends DataBase
{
    public function isSiteId(int $site): bool
    {
        $result = $this->dbQuery(
            'SELECT `sites`.`site_id` FROM `sites`
            INNER JOIN `users` ON `sites`.`user_id` = `users`.`user_id`
            WHERE `users`.`user_active` = 1 AND `sites`.`site_active` = 0
                AND `sites`.`site_id` = ' . $site
        );

        return ($this->dbFetchArray($result)) ? true : false;
    }

    public function getUserData(
        int $site,
        ?string &$user_login,
        ?string &$user_email
    ): bool {
        $result = $this->dbQuery(
            'SELECT `users`.`user_login`, `users`.`user_email` FROM `sites`
            INNER JOIN `users` ON `sites`.`user_id` = `users`.`user_id`
            WHERE `sites`.`site_id` = ' . $site
        );
        if ($row = $this->dbFetchArray($result)) {
            extract($row);

            return true;
        }

        return false;
    }

    public function deleteSiteData(int $site): bool
    {
        return $this->dbQuery(
            'DELETE FROM `sites`
            WHERE `sites`.`site_id` = ' . $site
        );
    }

    public function setSiteData(
        int $site,
        int $active,
        int $visible,
        string $name,
        string $www,
        string $ip,
        string $date
    ): bool {
        return $this->dbQuery(
            'UPDATE `sites`
            SET `sites`.`site_active` = ' . $active . ',
                `sites`.`site_visible` = ' . $visible . ",
                `sites`.`site_name` = '" . $name . "',
                `sites`.`site_url` = '" . $www . "',
                `sites`.`site_ip_updated` = '" . $ip . "',
                `sites`.`site_date_updated` = '" . $date . "'
            WHERE `sites`.`site_id` = " . $site
        );
    }

    public function getSiteData(
        int $site,
        ?int &$site_active,
        ?int &$site_visible,
        ?string &$site_name,
        ?string &$site_url,
        ?string &$user_login,
        ?string &$user_email
    ): bool {
        $result = $this->dbQuery(
            'SELECT `sites`.`site_active`, `sites`.`site_visible`,
                `sites`.`site_name`, `sites`.`site_url`, `users`.`user_login`,
                `users`.`user_email` FROM `sites`
            INNER JOIN `users` ON `sites`.`user_id` = `users`.`user_id`
            WHERE `sites`.`site_id` = ' . $site
        );
        if ($row = $this->dbFetchArray($result)) {
            extract($row);

            return true;
        }

        return false;
    }
}
