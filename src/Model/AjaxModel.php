<?php declare(strict_types=1);

// src/Model/AjaxModel.php
namespace App\Model;

use App\Core\DataBase;

class AjaxModel extends DataBase
{
    public function getCityList(int $province): array
    {
        $arrayResult = array();

        if ($province >= 1) {
            $result = $this->dbQuery(
                'SELECT `cities`.`city_id`, `cities`.`city_name` FROM `cities`
                INNER JOIN `provinces`
                    ON `cities`.`province_id` = `provinces`.`province_id`
                WHERE `cities`.`city_active` = 1
                    AND `provinces`.`province_active` = 1
                    AND `cities`.`province_id` = ' . $province . '
                ORDER BY `cities`.`city_name` ASC'
            );
            while ($row = $this->dbFetchArray($result)) {
                $arrayResult[$row['city_id']]['city_name'] = $row['city_name'];
            }
        }

        return $arrayResult;
    }
}
