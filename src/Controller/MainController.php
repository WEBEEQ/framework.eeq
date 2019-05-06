<?php
declare(strict_types = 1);

// src/Controller/MainController.php
namespace AppBundle\Controller;

use AppBundle\Model\MainModel;

class MainController
{
    public function mainAction(string $url, int $level): array
    {
        $mainModel = new MainModel();
        $mainModel->dbConnect();

        $userList = $mainModel->getUserList($level, $listLimit = 100);
        $pageNavigator = $mainModel->pageNavigator($url, $level, $listLimit);

        $mainModel->dbClose();

        return array(
            'listLimit' => $listLimit,
            'userList' => $userList,
            'pageNavigator' => $pageNavigator
        );
    }
}
