<?php
declare(strict_types = 1);

// src/Model/UserSearchModel.php
namespace AppBundle\Model;

use AppBundle\Bundle\Html;
use AppBundle\Core\DataBase;

class UserSearchModel extends DataBase
{
    public function getCityList(int $province): array
    {
        $arrayResult = array();

        if ($province >= 1) {
            $result = $this->dbQuery(
                'SELECT `cities`.`city_id`, `cities`.`city_name` FROM `cities`
                INNER JOIN `provinces` ON `cities`.`province_id` = `provinces`.`province_id`
                WHERE `cities`.`city_active` = 1 AND `provinces`.`province_active` = 1
                    AND `cities`.`province_id` = ' . $province . '
                ORDER BY `cities`.`city_name` ASC'
            );
            while ($row = $this->dbFetchArray($result)) {
                $arrayResult[$row['city_id']]['city_name'] = $row['city_name'];
            }
        }

        return $arrayResult;
    }

    public function getProvinceList(): array
    {
        $arrayResult = array();

        $result = $this->dbQuery(
            'SELECT `provinces`.`province_id`, `provinces`.`province_name` FROM `provinces`
            WHERE `provinces`.`province_active` = 1
            ORDER BY `provinces`.`province_name` ASC'
        );
        while ($row = $this->dbFetchArray($result)) {
            $arrayResult[$row['province_id']]['province_name'] = $row['province_name'];
        }

        return $arrayResult;
    }

    public function getUserList(
        string $name,
        string $surname,
        int $province,
        int $city,
        int $level,
        int $listLimit
    ): array {
        $arrayResult = array();
        $provinceId = ($province >= 1) ? ' AND `provinces`.`province_id` = ' . $province : '';
        $cityId = ($city >= 1) ? ' AND `cities`.`city_id` = ' . $city : '';
        $userName = ($name != '') ? " AND `users`.`user_name` LIKE '%" . $name . "%'" : '';
        $userSurname = ($surname != '') ? " AND `users`.`user_surname` LIKE '%" . $surname . "%'" : '';

        $result = $this->dbQuery(
            "SELECT * FROM `users`
            LEFT JOIN `provinces` ON `users`.`province_id` = `provinces`.`province_id`
            LEFT JOIN `cities` ON `users`.`city_id` = `cities`.`city_id`
            WHERE `users`.`user_active` = 1 AND `users`.`user_date` <= '" . date('Y-m-d H:i:s') . "'"
                . $provinceId . $cityId . $userName . $userSurname . '
            ORDER BY `users`.`user_number` DESC, `users`.`user_date` ASC LIMIT '
                . (($level - 1) * $listLimit) . ', ' . $listLimit
        );
        while ($row = $this->dbFetchArray($result)) {
            $arrayResult[$row['user_id']]['user_name'] = $row['user_name'];
            $arrayResult[$row['user_id']]['user_surname'] = $row['user_surname'];
            $arrayResult[$row['user_id']]['province_name'] = $row['province_name'];
            $arrayResult[$row['user_id']]['city_name'] = $row['city_name'];
            $arrayResult[$row['user_id']]['user_ranking'] = $row['user_ranking'];
        }

        return $arrayResult;
    }

    public function pageNavigator(
        string $url,
        string $parameter,
        string $name,
        string $surname,
        int $province,
        int $city,
        int $level,
        int $listLimit
    ): string {
        $count = 0;
        $provinceId = ($province >= 1) ? ' AND `provinces`.`province_id` = ' . $province : '';
        $cityId = ($city >= 1) ? ' AND `cities`.`city_id` = ' . $city : '';
        $userName = ($name != '') ? " AND `users`.`user_name` LIKE '%" . $name . "%'" : '';
        $userSurname = ($surname != '') ? " AND `users`.`user_surname` LIKE '%" . $surname . "%'" : '';

        $result = $this->dbQuery(
            "SELECT COUNT(*) AS `count` FROM `users`
            LEFT JOIN `provinces` ON `users`.`province_id` = `provinces`.`province_id`
            LEFT JOIN `cities` ON `users`.`city_id` = `cities`.`city_id`
            WHERE `users`.`user_active` = 1 AND `users`.`user_date` <= '" . date('Y-m-d H:i:s') . "'"
                . $provinceId . $cityId . $userName . $userSurname
        );
        if ($row = $this->dbFetchArray($result)) {
            $count = (is_numeric($row['count'])) ? (int) $row['count'] : 0;
        }

        return Html::preparePageNavigator($url . '/szukanie?' . $parameter . 'level=', $level, $listLimit, $count, 10);
    }
}
