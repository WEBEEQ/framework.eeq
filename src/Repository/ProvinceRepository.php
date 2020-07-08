<?php

declare(strict_types=1);

namespace App\Repository;

class ProvinceRepository
{
    protected object $database;
    protected object $manager;

    public function __construct(object $database, object $manager)
    {
        $this->database = $database;
        $this->manager = $manager;
    }

    public function getProvinceList(): array
    {
        $arrayResult = array();

        $query = $this->manager->createQuery(
            'SELECT p.`province_id`, p.`province_name` FROM `provinces` p
            WHERE p.`province_active` = 1
            ORDER BY p.`province_name` ASC'
        )->getStrQuery();
        $result = $this->database->dbQuery($query);
        while ($row = $this->database->dbFetchArray($result)) {
            $arrayResult[$row['province_id']]['province_name'] =
                $row['province_name'];
        }

        return $arrayResult;
    }
}
