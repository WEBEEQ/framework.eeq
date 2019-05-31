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

        $adminAccountModel->dbConnect();

        $adminAccountService = new AdminAccountService(
            $config,
            $adminAccountModel
        );
        $array = $adminAccountService->variableAction(
            $account,
            $level,
            $id
        );

        $adminAccountModel->dbClose();

        return $array;
    }
}
