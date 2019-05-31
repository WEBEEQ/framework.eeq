<?php declare(strict_types=1);

// src/Controller/AjaxController.php
namespace App\Controller;

use App\Model\AjaxModel;

class AjaxController
{
    public function cityListAction(int $province): array
    {
        $ajaxModel = new AjaxModel();

        $ajaxModel->dbConnect();

        $cityList = $ajaxModel->getCityList($province);

        $ajaxModel->dbClose();

        return array(
            'content' => 'src/View/ajax/city-list.php',
            'cityList' => $cityList
        );
    }
}
