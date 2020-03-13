<?php declare(strict_types=1);

// src/Controller/UserAccountController.php
namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Config, Token};
use App\Model\UserAccountModel;
use App\Service\UserAccountService;
use App\Validator\UserAccountValidator;

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
        $userAccountModel = new UserAccountModel($config, $html);
        $userAccountValidator = new UserAccountValidator($csrfToken);

        if ($account && $account != $id) {
            header('Location: ' . $config->getUrl() . '/logowanie');
            exit;
        }

        $userAccountModel->dbConnect();

        $userData = $userAccountModel->getUserData($id);
        if (!$userData) {
            $userAccountModel->dbClose();
            header('Location: ' . $config->getUrl() . '/logowanie');
            exit;
        }

        $userAccountService = new UserAccountService(
            $config,
            $html,
            $csrfToken,
            $userAccountModel,
            $userAccountValidator
        );
        $array = $userAccountService->variableAction(
            $userData,
            $name,
            $www,
            $submit,
            $token,
            $level,
            $id
        );

        $userAccountModel->dbClose();

        return $array;
    }
}
