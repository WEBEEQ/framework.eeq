<?php

declare(strict_types=1);

// src/Validator/ChangePasswordValidator.php
namespace App\Validator;

use App\Bundle\Error;

class ChangePasswordValidator extends Error
{
    protected $csrfToken;

    public function __construct(object $csrfToken)
    {
        parent::__construct();
        $this->csrfToken = $csrfToken;
    }

    public function validate(
        string $newPassword,
        string $repeatPassword,
        string $token
    ): void {
        if (strlen($newPassword) < 8) {
            $this->addError('Hasło musi zawierać minimalnie 8 znaków.');
        } elseif (strlen($repeatPassword) < 8) {
            $this->addError('Hasło musi zawierać minimalnie 8 znaków.');
        } else {
            $newPasswordStrlen = strlen($newPassword) > 30;
            $repeatPasswordStrlen = strlen($repeatPassword) > 30;
            if ($newPasswordStrlen || $repeatPasswordStrlen) {
                $this->addError('Hasło może zawierać maksymalnie 30 znaków.');
            }
        }
        if (!preg_match('/^([!@#$%^&*()0-9A-Za-z]*)$/', $newPassword)) {
            $this->addError('Hasło może składać się tylko z liter i cyfr.');
        } else {
            $pregMatch = preg_match(
                '/^([!@#$%^&*()0-9A-Za-z]*)$/',
                $repeatPassword
            );
            if (!$pregMatch) {
                $this->addError(
                    'Hasło może składać się tylko z liter i cyfr.'
                );
            }
        }
        if ($newPassword == '' || $repeatPassword == '') {
            $this->addError(
                'Nowe hasło lub powtórzone hasło nie zostało podane.'
            );
        }
        if ($newPassword != $repeatPassword) {
            $this->addError('Nowe hasło i powtórzone hasło nie są zgodne.');
        }
        if ($token != $this->csrfToken->receiveToken()) {
            $this->addError('Nieprawidłowy token przesyłanych danych.');
        }
    }
}
