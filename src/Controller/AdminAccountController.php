<?php declare(strict_types=1);

// src/Controller/AdminAccountController.php
namespace App\Controller;

use App\Bundle\Html;
use App\Core\Config;
use App\Model\AdminAccountModel;
use App\Service\AdminAccountService;

class AdminAccountController
{
    public function adminAccountAction(
        int $account,
        int $level,
        int $id
    ): array {
        $config = new Config();
        $html = new Html();
        $adminAccountModel = new AdminAccountModel($config, $html);

        if ($account && $account != $id) {
            header('Location: ' . $config->getUrl() . '/logowanie');
            exit;
        }

        $adminAccountModel->dbConnect();

        $userData = $adminAccountModel->getUserData($id);
        if (!$userData) {
            $adminAccountModel->dbClose();
            header('Location: ' . $config->getUrl() . '/logowanie');
            exit;
        }

        $adminAccountService = new AdminAccountService($adminAccountModel);
        $array = $adminAccountService->variableAction(
            $level,
            $id
        );

        $adminAccountModel->dbClose();

        return $array;
    }
}
