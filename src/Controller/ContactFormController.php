<?php

declare(strict_types=1);

namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Config, Email, Token};
use App\Service\ContactFormService;
use App\Validator\ContactFormValidator;

class ContactFormController
{
    public function contactFormAction(array $request, array $session): array
    {
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
            (string) $request['email'],
            (string) $request['subject'],
            (string) $request['message'],
            (bool) $request['submit'],
            (string) $request['token']
        );

        return $array;
    }
}
