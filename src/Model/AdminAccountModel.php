<?php declare(strict_types=1);

// src/Model/AdminAccountModel.php
namespace App\Model;

use App\Core\DataBase;

class AdminAccountModel extends DataBase
{
    protected $config;
    protected $html;

    public function __construct(object $config, object $html)
    {
        parent::__construct();
        $this->config = $config;
        $this->html = $html;
    }

    public function getUserData(int $id): array
    {
        $arrayResult = array();

        $result = $this->dbQuery(
            'SELECT `users`.`user_id` FROM `users`
            WHERE `users`.`user_active` = 1 AND `users`.`user_id` = ' . $id
        );
        if ($row = $this->dbFetchArray($result)) {
            $arrayResult['user_id'] = $row['user_id'];
        }

        return $arrayResult;
    }

    public function getSiteList(int $level, int $listLimit): array
    {
        $arrayResult = array();

        $result = $this->dbQuery(
            'SELECT `sites`.`site_id`, `sites`.`site_name` FROM `sites`
            INNER JOIN `users` ON `sites`.`user_id` = `users`.`user_id`
            WHERE `sites`.`site_active` = 0 AND `users`.`user_active` = 1
            ORDER BY `sites`.`site_date_added` DESC LIMIT '
                . (($level - 1) * $listLimit) . ', ' . $listLimit
        );
        while ($row = $this->dbFetchArray($result)) {
            $arrayResult[$row['site_id']]['site_name'] = $row['site_name'];
        }

        return $arrayResult;
    }

    public function pageNavigator(
        int $id,
        int $level,
        int $listLimit
    ): string {
        $count = 0;

        $result = $this->dbQuery(
            'SELECT COUNT(*) AS `count` FROM `sites`
            INNER JOIN `users` ON `sites`.`user_id` = `users`.`user_id`
            WHERE `sites`.`site_active` = 0 AND `users`.`user_active` = 1'
        );
        if ($row = $this->dbFetchArray($result)) {
            $count = (is_numeric($row['count'])) ? (int) $row['count'] : 0;
        }

        return $this->html->preparePageNavigator(
            $this->config->getUrl() . '/admin,' . $id . ',strona,',
            $level,
            $listLimit,
            $count,
            3
        );
    }
}
