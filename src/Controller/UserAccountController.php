<?php declare(strict_types=1);

// src/Controller/UserAccountController.php
namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Config, Token};
use App\Error\UserAccountError;
use App\Model\UserAccountModel;
use App\Service\UserAccountService;

class UserAccountController
{
    public function userAccountAction(
        string $name,
        string $www,
        bool $submit,
        string $token,
        int $account,
        int $level,
        int $id
    ): array {
        $config = new Config();
        $html = new Html();
        $csrfToken = new Token();
        $userAccountError = new UserAccountError($csrfToken);
        $userAccountModel = new UserAccountModel();
        $userAccountModel->dbConnect();

        $userAccountService = new UserAccountService(
            $config,
            $html,
            $csrfToken,
            $userAccountError,
            $userAccountModel
        );
        $array = $userAccountService->variableAction(
            $name,
            $www,
            $submit,
            $token,
            $account,
            $level,
            $id
        );

        $userAccountModel->dbClose();

        return $array;
    }
}
