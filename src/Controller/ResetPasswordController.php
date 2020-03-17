<?php

declare(strict_types=1);

// src/Controller/ResetPasswordController.php
namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Config, Email, Token};
use App\Model\ResetPasswordModel;
use App\Service\ResetPasswordService;
use App\Validator\ResetPasswordValidator;

class ResetPasswordController
{
    public function resetPasswordAction(
        string $login,
        bool $submit,
        string $token
    ): array {
        $config = new Config();
        $mail = new Email();
        $html = new Html();
        $csrfToken = new Token();
        $resetPasswordModel = new ResetPasswordModel();
        $resetPasswordValidator = new ResetPasswordValidator($csrfToken);

        $resetPasswordModel->dbConnect();

        $resetPasswordService = new ResetPasswordService(
            $config,
            $mail,
            $html,
            $csrfToken,
            $resetPasswordModel,
            $resetPasswordValidator
        );
        $array = $resetPasswordService->variableAction(
            $login,
            $submit,
            $token
        );

        $resetPasswordModel->dbClose();

        return $array;
    }
}
