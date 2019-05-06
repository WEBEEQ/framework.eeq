<?php declare(strict_types=1);

// src/Controller/UserAccountController.php
namespace App\Controller;

use App\Bundle\Html;
use App\Model\UserAccountModel;

class UserAccountController
{
    public function userAccountAction(
        string $url,
        string $name,
        string $www,
        bool $submit,
        int $level,
        string $remoteAddress,
        string $date,
        int $id
    ): array {
        $message = '';
        $ok = false;

        $userAccountModel = new UserAccountModel();
        $userAccountModel->dbConnect();

        $userData = $userAccountModel->getUserData($id);

        if (!$userData) {
            $userAccountModel->dbClose();
            header('Location: ' . $url . '/logowanie');
            exit;
        }

        if ($submit) {
            if (strlen($name) < 1) {
                $message .= 'Nazwa strony www musi zostać podana.' . "\r\n";
            } elseif (strlen($name) > 100) {
                $message .= 'Nazwa strony www może zawierać maksymalnie '
                    . '100 znaków.' . "\r\n";
            }
            $http = substr($www, 0, 7) != 'http://';
            $https = substr($www, 0, 8) != 'https://';
            if ($http && $https) {
                $message .= 'Url musi rozpoczynać się od znaków: http://'
                    . "\r\n";
            }
            if (strlen($www) > 100) {
                $message .= 'Url może zawierać maksymalnie 100 znaków.'
                    . "\r\n";
            }
            if ($message == '') {
                $siteData = $userAccountModel->addSiteData(
                    $id,
                    $name,
                    $www,
                    $remoteAddress,
                    $date
                );
                if ($siteData) {
                    $message .= 'Strona www została dodana i oczekuje '
                        . 'na akceptację.' . "\r\n";
                    $ok = true;
                    $name = '';
                    $www = '';
                } else {
                    $message .= 'Dodanie strony www nie powiodło się.'
                        . "\r\n";
                }
            }
        }

        $siteList = $userAccountModel->getSiteList(
            $id,
            $level,
            $listLimit = 10
        );
        $pageNavigator = $userAccountModel->pageNavigator(
            $url,
            $id,
            $level,
            $listLimit
        );

        $message = Html::prepareMessage($message, $ok);

        $userAccountModel->dbClose();

        return array(
            'message' => $message,
            'name' => $name,
            'www' => $www,
            'userData' => $userData,
            'siteList' => $siteList,
            'pageNavigator' => $pageNavigator
        );
    }
}
