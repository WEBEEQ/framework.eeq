<?php

declare(strict_types=1);

namespace App\Model\Api;

use App\Core\DataBase;

class DeleteSiteModel extends DataBase
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

        return (bool) $this->dbFetchArray($result);
    }

    public function deleteSiteData(int $site): bool
    {
        return $this->dbQuery(
            'DELETE FROM `sites`
            WHERE `sites`.`site_id` = ' . $site
        );
    }
}
