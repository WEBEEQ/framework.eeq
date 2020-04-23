<?php

declare(strict_types=1);

namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Config, Email, Token};
use App\Model\LogInUserModel;
use App\Service\LogInUserService;
use App\Validator\LogInUserValidator;

class LogInUserController
{
    public function logInUserAction(
        string $login,
        string $password,
        bool $remember,
        bool $submit,
        string $token
    ): array {
        $config = new Config();
        $mail = new Email();
        $html = new Html();
        $csrfToken = new Token();
        $logInUserModel = new LogInUserModel();
        $logInUserValidator = new LogInUserValidator($csrfToken);

        $logInUserModel->dbConnect();

        $logInUserService = new LogInUserService(
            $config,
            $mail,
            $html,
            $csrfToken,
            $logInUserModel,
            $logInUserValidator
        );
        $array = $logInUserService->variableAction(
            $login,
            $password,
            $remember,
            $submit,
            $token
        );

        $logInUserModel->dbClose();

        return $array;
    }
}
