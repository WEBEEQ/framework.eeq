<?php

declare(strict_types=1);

namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Config, Email, Token};
use App\Service\ContactFormService;
use App\Validator\ContactFormValidator;

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
        $contactFormValidator = new ContactFormValidator($csrfToken);

        $contactFormService = new ContactFormService(
            $config,
            $mail,
            $html,
            $csrfToken,
            $contactFormValidator
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
