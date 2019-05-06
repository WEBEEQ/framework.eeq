<?php
declare(strict_types = 1);

// src/Controller/MainPageController.php
namespace AppBundle\Controller;

use AppBundle\Model\MainPageModel;

class MainPageController
{
    public function mainPageAction(string $url, int $level): array
    {
        $mainPageModel = new MainPageModel();
        $mainPageModel->dbConnect();

        $userList = $mainPageModel->getUserList($level, $listLimit = 100);
        $pageNavigator = $mainPageModel->pageNavigator($url, $level, $listLimit);

        $mainPageModel->dbClose();

        return array(
            'listLimit' => $listLimit,
            'userList' => $userList,
            'pageNavigator' => $pageNavigator
        );
    }
}
