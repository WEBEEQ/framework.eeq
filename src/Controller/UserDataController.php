<?php
declare(strict_types = 1);

// src/Controller/UserDataController.php
namespace AppBundle\Controller;

use AppBundle\Core\Cache;
use AppBundle\Model\UserDataModel;

class UserDataController
{
    public function userDataAction(string $url, int $user): array
    {
        $cacheFile = 'cache/user-data-list_' . $user . '.html';
        $cacheTime = 7 * 24 * 60 * 60;

        $userDataModel = new UserDataModel();
        $userDataModel->dbConnect();

        if (!file_exists($cacheFile) || filemtime($cacheFile) <= time() - $cacheTime) {
            Cache::cachePage($url, $userDataModel->getRandomList(25), 'src/View/user-data-list.php', $cacheFile);
        }

        $userDataModel->updateUserNumber($user);
        $userDataModel->updateUserRating($user);
        $userData = $userDataModel->getUserData($user);

        if (!$userData) {
            header("HTTP/1.0 404 Not Found");
            include("error.php");
            exit;
        }

        $title = ($userData['user_name'] !== '' && $userData['user_surname'] !== '') ? $userData['user_name']
            . ' ' . $userData['user_surname'] . (($userData['province_name'] || $userData['city_name']) ? ' -'
            . (($userData['city_name']) ? ' ' . $userData['city_name'] : '') . (($userData['province_name']) ? ' '
            . $userData['province_name'] : '') : '') : '';

        $userDataModel->dbClose();

        return array(
            'cacheFile' => $cacheFile,
            'userData' => $userData,
            'title' => $title
        );
    }
}
