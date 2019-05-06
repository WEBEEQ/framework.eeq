<?php
declare(strict_types = 1);

// src/Model/AddModel.php
namespace AppBundle\Model;

use AppBundle\Core\DataBase;

class AddModel extends DataBase
{
    public function addUserData(
        int $province,
        int $city,
        string $name,
        string $surname,
        string $description,
        string $ip,
        string $date
    ): bool {
        $province = ($province >= 1) ? (string) $province : 'NULL';
        $city = ($city >= 1) ? (string) $city : 'NULL';

        return $this->dbQuery(
            'INSERT INTO `users` (
                `province_id`,
                `city_id`,
                `user_active`,
                `user_name`,
                `user_surname`,
                `user_description`,
                `user_ranking`,
                `user_number`,
                `user_ip`,
                `user_date`
            )
            VALUES (' . $province . ', ' . $city . ", 1, '" . $name . "', '" . $surname
                . "', '" . $description . "', 0, 0, '" . $ip . "', '" . $date . "')"
        );
    }

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
}
