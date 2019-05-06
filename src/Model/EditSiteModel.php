<?php declare(strict_types=1);

// src/Model/EditSiteModel.php
namespace App\Model;

use App\Core\DataBase;

class EditSiteModel extends DataBase
{
    public function isUserSiteId(int $id, int $site): bool
    {
        $result = $this->dbQuery(
            'SELECT `sites`.`site_id` FROM `sites`
            INNER JOIN `users` ON `sites`.`user_id` = `users`.`user_id`
            WHERE `users`.`user_active` = 1 AND `sites`.`site_active` = 1
                AND `sites`.`user_id` = ' . $id . '
                AND `sites`.`site_id` = ' . $site
        );

        return ($this->dbFetchArray($result)) ? true : false;
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

    public function getSiteData(
        int $site,
        ?int &$site_visible,
        ?string &$site_name,
        ?string &$site_url
    ): bool {
        $result = $this->dbQuery(
            'SELECT * FROM `sites`
            WHERE `sites`.`site_id` = ' . $site
        );
        if ($row = $this->dbFetchArray($result)) {
            extract($row);

            return true;
        }

        return false;
    }
}
