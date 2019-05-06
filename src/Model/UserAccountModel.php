<?php declare(strict_types=1);

// src/Model/UserAccountModel.php
namespace App\Model;

use App\Bundle\Html;
use App\Core\DataBase;

class UserAccountModel extends DataBase
{
    public function getUserData(int $id): array
    {
        $arrayResult = array();

        $result = $this->dbQuery(
            'SELECT `users`.`user_id`, `users`.`user_name`,
                `users`.`user_surname`, `users`.`user_show` FROM `users`
            WHERE `users`.`user_active` = 1 AND `users`.`user_id` = ' . $id
        );
        if ($row = $this->dbFetchArray($result)) {
            $arrayResult['user_id'] = $row['user_id'];
            $arrayResult['user_name'] = $row['user_name'];
            $arrayResult['user_surname'] = $row['user_surname'];
            $arrayResult['user_show'] = $row['user_show'];
        }

        return $arrayResult;
    }

    public function getSiteList(int $id, int $level, int $listLimit): array
    {
        $arrayResult = array();

        $result = $this->dbQuery(
            'SELECT `sites`.`site_id`, `sites`.`site_name` FROM `sites`
            INNER JOIN `users` ON `sites`.`user_id` = `users`.`user_id`
            WHERE `sites`.`site_active` = 1 AND `users`.`user_active` = 1
                AND `sites`.`user_id` = ' . $id . '
            ORDER BY `sites`.`site_date_added` DESC LIMIT '
                . (($level - 1) * $listLimit) . ', ' . $listLimit
        );
        while ($row = $this->dbFetchArray($result)) {
            $arrayResult[$row['site_id']]['site_name'] = $row['site_name'];
        }

        return $arrayResult;
    }

    public function pageNavigator(
        string $url,
        int $id,
        int $level,
        int $listLimit
    ): string {
        $count = 0;

        $result = $this->dbQuery(
            'SELECT COUNT(*) AS `count` FROM `sites`
            INNER JOIN `users` ON `sites`.`user_id` = `users`.`user_id`
            WHERE `sites`.`site_active` = 1 AND `users`.`user_active` = 1
                AND `sites`.`user_id` = ' . $id
        );
        if ($row = $this->dbFetchArray($result)) {
            $count = (is_numeric($row['count'])) ? (int) $row['count'] : 0;
        }

        return Html::preparePageNavigator(
            $url . '/konto,' . $id . ',strona,',
            $level,
            $listLimit,
            $count,
            3
        );
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
