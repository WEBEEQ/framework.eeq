<?php
declare(strict_types = 1);

// src/Controller/AjaxCityController.php
namespace AppBundle\Controller;

use AppBundle\Model\AjaxCityModel;

class AjaxCityController
{
    public function ajaxCityAction(int $province): array
    {
        $ajaxCityModel = new AjaxCityModel();
        $ajaxCityModel->dbConnect();

        $cityList = $ajaxCityModel->getCityList($province);

        $ajaxCityModel->dbClose();

        return array(
            'cityList' => $cityList
        );
    }
}
