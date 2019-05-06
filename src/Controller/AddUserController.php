<?php declare(strict_types=1);

// src/Controller/AddUserController.php
namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Config, Email, Token};
use App\Error\AddUserError;
use App\Model\AddUserModel;
use App\Service\AddUserService;

class AddUserController
{
    public function addUserAction(
        string $name,
        string $surname,
        string $login,
        string $password,
        string $repeatPassword,
        string $email,
        string $repeatEmail,
        bool $accept,
        bool $submit,
        string $token,
        string $user,
        string $code
    ): array {
        $config = new Config();
        $mail = new Email();
        $html = new Html();
        $csrfToken = new Token();
        $addUserError = new AddUserError($csrfToken);
        $addUserModel = new AddUserModel();
        $addUserModel->dbConnect();

        $addUserService = new AddUserService(
            $config,
            $mail,
            $html,
            $csrfToken,
            $addUserError,
            $addUserModel
        );
        $array = $addUserService->variableAction(
            $name,
            $surname,
            $login,
            $password,
            $repeatPassword,
            $email,
            $repeatEmail,
            $accept,
            $submit,
            $token,
            $user,
            $code
        );

        $addUserModel->dbClose();

        return $array;
    }
}
