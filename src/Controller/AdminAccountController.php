<?php declare(strict_types=1);

// src/Controller/AdminAccountController.php
namespace App\Controller;

use App\Model\AdminAccountModel;

class AdminAccountController
{
    public function adminAccountAction(
        string $url,
        int $level,
        int $id
    ): array {
        $adminAccountModel = new AdminAccountModel();
        $adminAccountModel->dbConnect();

        $userData = $adminAccountModel->getUserData($id);

        if (!$userData) {
            $adminAccountModel->dbClose();
            header('Location: ' . $url . '/logowanie');
            exit;
        }

        $siteList = $adminAccountModel->getSiteList($level, $listLimit = 10);
        $pageNavigator = $adminAccountModel->pageNavigator(
            $url,
            $id,
            $level,
            $listLimit
        );

        $adminAccountModel->dbClose();

        return array(
            'siteList' => $siteList,
            'pageNavigator' => $pageNavigator
        );
    }
}
