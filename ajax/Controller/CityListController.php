<?php
declare(strict_types = 1);

// ajax/Controller/CityListController.php
namespace Ajax\Controller;

use Ajax\Model\CityListModel;

class CityListController
{
    public function cityListAction(int $province): array
    {
        $cityListModel = new CityListModel();
        $cityListModel->dbConnect();

        $cityList = $cityListModel->getCityList($province);

        $cityListModel->dbClose();

        return array('cityList' => $cityList);
    }
}
