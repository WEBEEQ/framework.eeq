<?php declare(strict_types=1);

// src/Controller/EditUserController.php
namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Config, Email, Token};
use App\Model\EditUserModel;
use App\Service\EditUserService;
use App\Validator\EditUserValidator;

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
        $editUserModel = new EditUserModel();
        $editUserValidator = new EditUserValidator($csrfToken, $editUserModel);

        $editUserModel->dbConnect();

        if (!$editUserModel->isUserId($id, $user)) {
            $editUserModel->dbClose();
            header('Location: ' . $config->getUrl() . '/logowanie');
            exit;
        }

        $editUserService = new EditUserService(
            $config,
            $mail,
            $html,
            $csrfToken,
            $editUserModel,
            $editUserValidator
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
            $login
        );

        $editUserModel->dbClose();

        return $array;
    }
}
