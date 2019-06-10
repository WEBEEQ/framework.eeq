<?php declare(strict_types=1);

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
        $error = '';

        if (strlen($newPassword) < 8) {
            $error .= 'Hasło musi zawierać minimalnie 8 znaków.'
                . "\r\n";
        } elseif (strlen($repeatPassword) < 8) {
            $error .= 'Hasło musi zawierać minimalnie 8 znaków.'
                . "\r\n";
        } else {
            $newPasswordStrlen = strlen($newPassword) > 30;
            $repeatPasswordStrlen = strlen($repeatPassword) > 30;
            if ($newPasswordStrlen || $repeatPasswordStrlen) {
                $error .= 'Hasło może zawierać maksymalnie 30 znaków.'
                    . "\r\n";
            }
        }
        if (!preg_match('/^([!@#$%^&*()0-9A-Za-z]*)$/', $newPassword)) {
            $error .= 'Hasło może składać się tylko z liter i cyfr.'
                . "\r\n";
        } else {
            $pregMatch = preg_match(
                '/^([!@#$%^&*()0-9A-Za-z]*)$/',
                $repeatPassword
            );
            if (!$pregMatch) {
                $error .= 'Hasło może składać się tylko z liter i cyfr.'
                    . "\r\n";
            }
        }
        if ($newPassword == '' || $repeatPassword == '') {
            $error .= 'Nowe hasło lub powtórzone hasło '
                . 'nie zostało podane.' . "\r\n";
        }
        if ($newPassword != $repeatPassword) {
            $error .= 'Nowe hasło i powtórzone hasło nie są zgodne.'
                . "\r\n";
        }
        if ($token != $this->csrfToken->receiveToken()) {
            $error .= 'Nieprawidłowy token przesyłanych danych.' . "\r\n";
        }

        $this->setError($error);
    }
}
