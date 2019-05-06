<?php declare(strict_types=1);

// src/Controller/LoginUserController.php
namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Config, Email, Token};
use App\Error\LoginUserError;
use App\Model\LoginUserModel;
use App\Service\LoginUserService;

class LoginUserController
{
    public function loginUserAction(
        string $login,
        string $password,
        bool $forget,
        bool $remember,
        bool $submit,
        string $token,
        string $user,
        string $code
    ): array {
        $config = new Config();
        $mail = new Email();
        $html = new Html();
        $csrfToken = new Token();
        $loginUserError = new LoginUserError($csrfToken);
        $loginUserModel = new LoginUserModel();
        $loginUserModel->dbConnect();

        $loginUserService = new LoginUserService(
            $config,
            $mail,
            $html,
            $csrfToken,
            $loginUserError,
            $loginUserModel
        );
        $array = $loginUserService->variableAction(
            $login,
            $password,
            $forget,
            $remember,
            $submit,
            $token,
            $user,
            $code
        );

        $loginUserModel->dbClose();

        return $array;
    }
}
