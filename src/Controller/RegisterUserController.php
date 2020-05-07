<?php

declare(strict_types=1);

namespace App\Controller;

use App\Bundle\{Html, Key};
use App\Core\{Config, Email, Token};
use App\Model\RegisterUserModel;
use App\Service\RegisterUserService;
use App\Validator\RegisterUserValidator;

class RegisterUserController
{
    public function registerUserAction(
        string $name,
        string $surname,
        string $login,
        string $password,
        string $repeatPassword,
        string $email,
        string $repeatEmail,
        bool $accept,
        bool $submit,
        string $token
    ): array {
        $config = new Config();
        $mail = new Email();
        $html = new Html();
        $key = new Key();
        $csrfToken = new Token();
        $registerUserModel = new RegisterUserModel();
        $registerUserValidator = new RegisterUserValidator(
            $csrfToken,
            $registerUserModel
        );

        $registerUserModel->dbConnect();

        $registerUserService = new RegisterUserService(
            $config,
            $mail,
            $html,
            $key,
            $csrfToken,
            $registerUserModel,
            $registerUserValidator
        );
        $array = $registerUserService->variableAction(
            $name,
            $surname,
            $login,
            $password,
            $repeatPassword,
            $email,
            $repeatEmail,
            $accept,
            $submit,
            $token
        );

        $registerUserModel->dbClose();

        return $array;
    }
}
