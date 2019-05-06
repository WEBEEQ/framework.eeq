<?php
declare(strict_types = 1);

// src/Controller/UserController.php
namespace AppBundle\Controller;

use AppBundle\Core\Cache;
use AppBundle\Model\UserModel;

class UserController
{
    public function userAction(string $url, int $user): array
    {
        $cacheFile = 'cache/user-list_' . $user . '.html';
        $cacheTime = 7 * 24 * 60 * 60;

        $userModel = new UserModel();
        $userModel->dbConnect();

        if (!file_exists($cacheFile) || filemtime($cacheFile) <= time() - $cacheTime) {
            Cache::cachePage($url, $userModel->getRandomList(25), 'src/View/user-list.php', $cacheFile);
        }

        $userModel->updateUserNumber($user);
        $userModel->updateUserRating($user);
        $userData = $userModel->getUserData($user);

        if (!$userData) {
            header("HTTP/1.0 404 Not Found");
            include("error.php");
            exit;
        }

        $title = ($userData['user_name'] !== '' && $userData['user_surname'] !== '') ? $userData['user_name']
            . ' ' . $userData['user_surname'] . (($userData['province_name'] || $userData['city_name']) ? ' -'
            . (($userData['city_name']) ? ' ' . $userData['city_name'] : '') . (($userData['province_name']) ? ' '
            . $userData['province_name'] : '') : '') : '';

        $userModel->dbClose();

        return array(
            'cacheFile' => $cacheFile,
            'userData' => $userData,
            'title' => $title
        );
    }
}
