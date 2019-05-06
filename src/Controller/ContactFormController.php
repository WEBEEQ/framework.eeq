<?php declare(strict_types=1);

// src/Controller/ContactFormController.php
namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Config, Email, Token};
use App\Error\ContactFormError;
use App\Service\ContactFormService;

class ContactFormController
{
    public function contactFormAction(
        string $email,
        string $subject,
        string $text,
        bool $submit,
        string $token
    ): array {
        $config = new Config();
        $mail = new Email();
        $html = new Html();
        $csrfToken = new Token();
        $contactFormError = new ContactFormError($csrfToken);

        $contactFormService = new ContactFormService(
            $config,
            $mail,
            $html,
            $csrfToken,
            $contactFormError
        );
        $array = $contactFormService->variableAction(
            $email,
            $subject,
            $text,
            $submit,
            $token
        );

        return $array;
    }
}
