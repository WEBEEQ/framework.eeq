<?php

declare(strict_types=1);

// src/Controller/Ajax/CityListController.php
namespace App\Controller\Ajax;

use App\Model\Ajax\CityListModel;

class CityListController
{
    public function cityListAction(int $province): array
    {
        $cityListModel = new CityListModel();

        $cityListModel->dbConnect();

        $cityList = $cityListModel->getCityList($province);

        $cityListModel->dbClose();

        return array(
            'content' => 'src/View/ajax/city-list.php',
            'cityList' => $cityList
        );
    }
}
