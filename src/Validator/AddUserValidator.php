<?php declare(strict_types=1);

// src/Validator/AddUserValidator.php
namespace App\Validator;

use App\Bundle\Error;

class AddUserValidator extends Error
{
    protected $csrfToken;
    protected $addUserModel;

    public function __construct(object $csrfToken, object $addUserModel)
    {
        parent::__construct();
        $this->csrfToken = $csrfToken;
        $this->addUserModel = $addUserModel;
    }

    public function validate(
        string $name,
        string $surname,
        string $login,
        string $password,
        string $repeatPassword,
        string $email,
        string $repeatEmail,
        bool $accept,
        string $token
    ): void {
        $error = '';

        if (strlen($name) < 1) {
            $error .= 'Imię musi zostać podane.' . "\r\n";
        } elseif (strlen($name) > 30) {
            $error .= 'Imię może zawierać maksymalnie 30 znaków.'
                . "\r\n";
        }
        if (strlen($surname) < 1) {
            $error .= 'Nazwisko musi zostać podane.' . "\r\n";
        } elseif (strlen($surname) > 50) {
            $error .= 'Nazwisko może zawierać maksymalnie 50 znaków.'
                . "\r\n";
        }
        if (strlen($login) < 3) {
            $error .= 'Login musi zawierać minimalnie 3 znaki.' . "\r\n";
        } elseif (strlen($login) > 20) {
            $error .= 'Login może zawierać maksymalnie 20 znaków.'
                . "\r\n";
        }
        if (!preg_match('/^([0-9A-Za-z]*)$/', $login)) {
            $error .= 'Login może składać się tylko z liter i cyfr.'
                . "\r\n";
        }
        if ($login != '' && $this->addUserModel->isUserLogin($login)) {
            $error .= 'Konto o podanym loginie już istnieje.' . "\r\n";
        }
        if (strlen($password) < 8 || strlen($repeatPassword) < 8) {
            $error .= 'Hasło musi zawierać minimalnie 8 znaków.'
                . "\r\n";
        } elseif (strlen($password) > 30 || strlen($repeatPassword) > 30) {
            $error .= 'Hasło może zawierać maksymalnie 30 znaków.'
                . "\r\n";
        }
        if (!preg_match('/^([!@#$%^&*()0-9A-Za-z]*)$/', $password)) {
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
        if ($password != $repeatPassword) {
            $error .= 'Hasło i powtórzone hasło nie są zgodne.' . "\r\n";
        }
        if (strlen($email) > 100 || strlen($repeatEmail) > 100) {
            $error .= 'E-mail może zawierać maksymalnie 100 znaków.'
                . "\r\n";
        }
        $pregMatch = preg_match(
            '/^([0-9A-Za-z._-]+)@([0-9A-Za-z-]+\.)+([0-9A-Za-z]{1,63})$/',
            $email
        );
        if (!$pregMatch) {
            $error .= 'E-mail musi mieć format zapisu: '
                . 'nazwisko@domena.pl' . "\r\n";
        } else {
            $pregMatch = preg_match(
                '/^([0-9A-Za-z._-]+)@([0-9A-Za-z-]+\.)+([0-9A-Za-z]{1,63})$/',
                $repeatEmail
            );
            if (!$pregMatch) {
                $error .= 'E-mail musi mieć format zapisu: '
                    . 'nazwisko@domena.pl' . "\r\n";
            }
        }
        if ($email != $repeatEmail) {
            $error .= 'E-mail i powtórzony e-mail nie są zgodne.'
                . "\r\n";
        }
        if (!$accept) {
            $error .= 'Musisz zaakceptować regulamin serwisu.' . "\r\n";
        }
        if ($token != $this->csrfToken->receiveToken()) {
            $error .= 'Nieprawidłowy token przesyłanych danych.' . "\r\n";
        }

        $this->setError($error);
    }
}
