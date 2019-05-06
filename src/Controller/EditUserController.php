<?php declare(strict_types=1);

// src/Controller/EditUserController.php
namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Config, Email, Token};
use App\Error\EditUserError;
use App\Model\EditUserModel;
use App\Service\EditUserService;

class EditUserController
{
    public function editUserAction(
        string $lastLogin,
        string $password,
        string $newPassword,
        string $repeatPassword,
        string $name,
        string $surname,
        string $street,
        string $postcode,
        int $province,
        int $city,
        string $phone,
        string $email,
        string $newEmail,
        string $repeatEmail,
        string $www,
        string $description,
        bool $submit,
        string $token,
        int $user,
        int $id,
        string $login
    ): array {
        $config = new Config();
        $mail = new Email();
        $html = new Html();
        $csrfToken = new Token();
        $editUserError = new EditUserError($csrfToken);
        $editUserModel = new EditUserModel();
        $editUserModel->dbConnect();

        $editUserService = new EditUserService(
            $config,
            $mail,
            $html,
            $csrfToken,
            $editUserError,
            $editUserModel
        );
        $array = $editUserService->variableAction(
            $lastLogin,
            $password,
            $newPassword,
            $repeatPassword,
            $name,
            $surname,
            $street,
            $postcode,
            $province,
            $city,
            $phone,
            $email,
            $newEmail,
            $repeatEmail,
            $www,
            $description,
            $submit,
            $token,
            $user,
            $id,
            $login
        );

        $editUserModel->dbClose();

        return $array;
    }
}
