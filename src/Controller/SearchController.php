<?php
declare(strict_types = 1);

// src/Controller/SearchController.php
namespace AppBundle\Controller;

use AppBundle\Model\SearchModel;

class SearchController
{
    public function searchAction(
        string $url,
        string $parameter,
        string $name,
        string $surname,
        int $province,
        int $city,
        bool $submit,
        int $level
    ): array {
        $searchModel = new SearchModel();
        $searchModel->dbConnect();

        $provinceList = $searchModel->getProvinceList();
        $cityList = $searchModel->getCityList($province);
        $userList = '';
        $pageNavigator = '';

        if ($submit) {
            $userList = $searchModel->getUserList($name, $surname, $province, $city, $level, 100);
            $pageNavigator = $searchModel->pageNavigator($url, $parameter, $name, $surname, $province, $city, $level, 100);
        }

        $searchModel->dbClose();

        return array(
            'provinceList' => $provinceList,
            'cityList' => $cityList,
            'userList' => $userList,
            'pageNavigator' => $pageNavigator
        );
    }
}
