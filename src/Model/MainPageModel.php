<?php
declare(strict_types = 1);

// src/Model/MainPageModel.php
namespace AppBundle\Model;

use AppBundle\Bundle\Html;
use AppBundle\Core\DataBase;

class MainPageModel extends DataBase
{
    public function getUserList(int $level, int $listLimit): array
    {
        $arrayResult = array();

        $result = $this->dbQuery(
            "SELECT * FROM `users`
            LEFT JOIN `provinces` ON `users`.`province_id` = `provinces`.`province_id`
            LEFT JOIN `cities` ON `users`.`city_id` = `cities`.`city_id`
            WHERE `users`.`user_active` = 1 AND `users`.`user_date` <= '" . date('Y-m-d H:i:s') . "'
            ORDER BY `users`.`user_number` DESC, `users`.`user_date` ASC LIMIT "
                . (($level - 1) * $listLimit) . ', ' . $listLimit
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

    public function pageNavigator(string $url, int $level, int $listLimit): string
    {
        $count = 0;

        $result = $this->dbQuery(
            "SELECT COUNT(*) AS `count` FROM `users`
            LEFT JOIN `provinces` ON `users`.`province_id` = `provinces`.`province_id`
            LEFT JOIN `cities` ON `users`.`city_id` = `cities`.`city_id`
            WHERE `users`.`user_active` = 1 AND `users`.`user_date` <= '" . date('Y-m-d H:i:s') . "'"
        );
        if ($row = $this->dbFetchArray($result)) {
            $count = (is_numeric($row['count'])) ? (int) $row['count'] : 0;
        }

        return Html::preparePageNavigator($url . '/strona,', $level, $listLimit, $count, 10);
    }
}
