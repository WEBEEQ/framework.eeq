<?php
declare(strict_types = 1);

// src/Controller/UserSearchController.php
namespace AppBundle\Controller;

use AppBundle\Model\UserSearchModel;

class UserSearchController
{
    public function userSearchAction(
        string $url,
        string $parameter,
        string $name,
        string $surname,
        int $province,
        int $city,
        bool $submit,
        int $level
    ): array {
        $userSearchModel = new UserSearchModel();
        $userSearchModel->dbConnect();

        $provinceList = $userSearchModel->getProvinceList();
        $cityList = $userSearchModel->getCityList($province);
        $userList = '';
        $pageNavigator = '';

        if ($submit) {
            $userList = $userSearchModel->getUserList($name, $surname, $province, $city, $level, 100);
            $pageNavigator = $userSearchModel->pageNavigator(
                $url,
                $parameter,
                $name,
                $surname,
                $province,
                $city,
                $level,
                100
            );
        }

        $userSearchModel->dbClose();

        return array(
            'provinceList' => $provinceList,
            'cityList' => $cityList,
            'userList' => $userList,
            'pageNavigator' => $pageNavigator
        );
    }
}
