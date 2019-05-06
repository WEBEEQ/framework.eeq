<?php
declare(strict_types = 1);

// src/Controller/AddUserController.php
namespace AppBundle\Controller;

use AppBundle\Bundle\Html;
use AppBundle\Model\AddUserModel;

class AddUserController
{
    public function addUserAction(
        string $name,
        string $surname,
        int $province,
        int $city,
        string $description,
        bool $submit,
        string $remoteAddress,
        string $date
    ): array {
        $message = '';
        $ok = false;

        $addUserModel = new AddUserModel();
        $addUserModel->dbConnect();

        if ($submit) {
            if (strlen($name) < 1) {
                $message .= 'Imię musi zostać podane.' . "\r\n";
            } elseif (strlen($name) > 50) {
                $message .= 'Imię może zawierać maksymalnie 50 znaków.' . "\r\n";
            }
            if (strlen($surname) < 1) {
                $message .= 'Nazwisko musi zostać podane.' . "\r\n";
            } elseif (strlen($surname) > 100) {
                $message .= 'Nazwisko może zawierać maksymalnie 100 znaków.' . "\r\n";
            }
            if ($message == '') {
                $userData = $addUserModel->addUserData(
                    $province,
                    $city,
                    $name,
                    $surname,
                    $description,
                    $remoteAddress,
                    $date
                );

                if ($userData) {
                    $message .= 'Dane osoby zostały dodane.' . "\r\n";
                    $ok = true;
                    $name = '';
                    $surname = '';
                    $province = 0;
                    $city = 0;
                    $description = '';
                } else {
                    $message .= 'Dodanie danych osoby nie powiodło się.' . "\r\n";
                }
            }
        }

        $provinceList = $addUserModel->getProvinceList();
        $cityList = $addUserModel->getCityList($province);

        $message = Html::prepareMessage($message, $ok);

        $addUserModel->dbClose();

        return array(
            'message' => $message,
            'name' => $name,
            'surname' => $surname,
            'province' => $province,
            'city' => $city,
            'description' => $description,
            'provinceList' => $provinceList,
            'cityList' => $cityList
        );
    }
}
