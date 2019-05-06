<?php
declare(strict_types = 1);

// src/Model/UserModel.php
namespace AppBundle\Model;

use AppBundle\Core\DataBase;

class UserModel extends DataBase
{
    public function getUserData(int $user): array
    {
        $arrayResult = array();

        $result = $this->dbQuery(
            "SELECT * FROM `users`
            LEFT JOIN `provinces` ON `users`.`province_id` = `provinces`.`province_id`
            LEFT JOIN `cities` ON `users`.`city_id` = `cities`.`city_id`
            WHERE `users`.`user_active` = 1 AND `users`.`user_date` <= '"
                . date('Y-m-d H:i:s') . "' AND `users`.`user_id` = " . $user
        );
        if ($row = $this->dbFetchArray($result)) {
            $arrayResult['user_name'] = $row['user_name'];
            $arrayResult['user_surname'] = $row['user_surname'];
            $arrayResult['user_ranking'] = $row['user_ranking'];
            $arrayResult['user_description'] = $row['user_description'];
            $arrayResult['province_name'] = $row['province_name'];
            $arrayResult['city_name'] = $row['city_name'];
        }

        return $arrayResult;
    }

    public function getRandomList(int $listLimit): array
    {
        $arrayResult = array();

        $result = $this->dbQuery(
            "SELECT * FROM `users`
            LEFT JOIN `provinces` ON `users`.`province_id` = `provinces`.`province_id`
            LEFT JOIN `cities` ON `users`.`city_id` = `cities`.`city_id`
            WHERE `users`.`user_active` = 1 AND `users`.`user_date` <= '" . date('Y-m-d H:i:s') . "'
            ORDER BY RAND() LIMIT 0, " . $listLimit
        );
        while ($row = $this->dbFetchArray($result)) {
            $arrayResult[$row['user_id']]['user_name'] = $row['user_name'];
            $arrayResult[$row['user_id']]['user_surname'] = $row['user_surname'];
            $arrayResult[$row['user_id']]['user_ranking'] = $row['user_ranking'];
            $arrayResult[$row['user_id']]['province_name'] = $row['province_name'];
            $arrayResult[$row['user_id']]['city_name'] = $row['city_name'];
        }

        return $arrayResult;
    }

    public function updateUserNumber(int $user): bool
    {
        return $this->dbQuery(
            "UPDATE `users` SET `users`.`user_number` = (`users`.`user_number` + 1)
            WHERE `users`.`user_active` = 1 AND `users`.`user_date` <= '"
                . date('Y-m-d H:i:s') . "' AND `users`.`user_id` = " . $user
        );
    }

    public function updateUserRating(int $user): bool
    {
        $result = $this->dbQuery(
            "SELECT MAX(`users`.`user_number`) AS `max` FROM `users`
            WHERE `users`.`user_active` = 1 AND `users`.`user_date` <= '" . date('Y-m-d H:i:s') . "'"
        );
        if ($row = $this->dbFetchArray($result)) {
            $max = $row['max'];
        }

        return $this->dbQuery(
            'UPDATE `users` SET `users`.`user_ranking` = (' . $max . " / `users`.`user_number`)
            WHERE `users`.`user_active` = 1 AND `users`.`user_date` <= '"
                . date('Y-m-d H:i:s') . "' AND `users`.`user_id` = " . $user
        );
    }
}
