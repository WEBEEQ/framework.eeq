<?php

declare(strict_types=1);

namespace App\Model;

use App\Core\DataBase;

class ShowSiteModel extends DataBase
{
    public function isUserMaxShow(int $id, int $show = 0): bool
    {
        $result = $this->dbQuery(
            'SELECT `users`.`user_id` FROM `users`
            WHERE `users`.`user_show` >= (
                SELECT COUNT(*) FROM `sites`
                INNER JOIN `users` ON `sites`.`user_id` = `users`.`user_id`
                WHERE `sites`.`site_active` = 1 AND `sites`.`site_visible` = 1
                    AND `users`.`user_active` = 1
                    AND `users`.`user_show` >= ' . $show . '
                    AND `users`.`user_id` != ' . $id . '
            ) AND `users`.`user_id` = ' . $id
        );

        return (bool) $this->dbFetchArray($result);
    }

    public function getSiteRandomUrl(
        int $id,
        ?int &$user_id,
        ?int &$user_show,
        int $show = 1
    ): string {
        $result = $this->dbQuery(
            'SELECT `sites`.`site_url`, `users`.`user_id`,
                `users`.`user_show` FROM `sites`
            INNER JOIN `users` ON `sites`.`user_id` = `users`.`user_id`
            WHERE `sites`.`site_active` = 1 AND `sites`.`site_visible` = 1
                AND `users`.`user_active` = 1
                AND `users`.`user_show` >= ' . $show . '
                AND `users`.`user_id` != ' . $id . '
            ORDER BY RAND() LIMIT 1'
        );
        if ($row = $this->dbFetchArray($result)) {
            extract($row);

            return $row['site_url'];
        }

        return '';
    }

    public function setUserShow(
        int $id,
        int $user,
        int $userShow,
        int $show = 1
    ): bool {
        $this->dbStartTransaction();
        $result1 = $this->dbQuery(
            'UPDATE `users`
            SET `users`.`user_show` = `users`.`user_show` + 1
            WHERE `users`.`user_id` = ' . $id
        );
        if ($result1 && ($userShow < 1 || $show < 1)) {
            $this->dbCommit();

            return true;
        }
        $result2 = $this->dbQuery(
            'UPDATE `users`
            SET `users`.`user_show` = `users`.`user_show` - ' . $show . '
            WHERE `users`.`user_id` = ' . $user
        );
        if ($result1 && $result2) {
            $this->dbCommit();

            return true;
        } else {
            $this->dbRollback();
        }

        return false;
    }
}
