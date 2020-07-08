<?php

declare(strict_types=1);

namespace App\Repository;

class CityRepository
{
    protected object $database;
    protected object $manager;

    public function __construct(object $database, object $manager)
    {
        $this->database = $database;
        $this->manager = $manager;
    }

    public function getCityList(int $province): array
    {
        $arrayResult = array();

        if ($province >= 1) {
            $query = $this->manager->createQuery(
                'SELECT c.`city_id`, c.`city_name` FROM `cities` c
                INNER JOIN `provinces` p ON c.`province_id` = p.`province_id`
                WHERE c.`city_active` = 1 AND p.`province_active` = 1
                    AND c.`province_id` = :province
                ORDER BY c.`city_name` ASC'
            )
                ->setParameter('province', $province)
                ->getStrQuery();
            $result = $this->database->dbQuery($query);
            while ($row = $this->database->dbFetchArray($result)) {
                $arrayResult[$row['city_id']]['city_name'] = $row['city_name'];
            }
        }

        return $arrayResult;
    }
}
