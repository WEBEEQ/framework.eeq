<?php

declare(strict_types=1);

// src/Validator/EditUserValidator.php
namespace App\Validator;

use App\Bundle\Error;

class EditUserValidator extends Error
{
    protected $csrfToken;
    protected $editUserModel;

    public function __construct(object $csrfToken, object $editUserModel)
    {
        parent::__construct();
        $this->csrfToken = $csrfToken;
        $this->editUserModel = $editUserModel;
    }

    public function validate(
        string $password,
        string $newPassword,
        string $repeatPassword,
        string $name,
        string $surname,
        string $email,
        string $newEmail,
        string $repeatEmail,
        string $www,
        string $token,
        int $user
    ): void {
        if ($newPassword != '' && strlen($newPassword) < 8) {
            $this->addError('Hasło musi zawierać minimalnie 8 znaków.');
        } elseif ($repeatPassword != '' && strlen($repeatPassword) < 8) {
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
        if ($password != '') {
            $userPassword = $this->editUserModel->getUserPassword($user);
            if (!password_verify($password, $userPassword)) {
                $this->addError(
                    'Stare hasło nie jest zgodne z dotychczas istniejącym.'
                );
            }
        }
        $pass = ($newPassword != '' || $repeatPassword != '');
        if ($password == '' && $pass && $newPassword == $repeatPassword) {
            $this->addError('Stare hasło nie zostało podane.');
        }
        $pass = ($newPassword == '' || $repeatPassword == '');
        if ($password != '' && $pass) {
            $this->addError(
                'Nowe hasło lub powtórzone hasło nie zostało podane.'
            );
        }
        if ($newPassword != $repeatPassword) {
            $this->addError('Nowe hasło i powtórzone hasło nie są zgodne.');
        }
        if ($password != '' && $password == $newPassword) {
            $this->addError('Nowe hasło i stare hasło nie mogą być zgodne.');
        }
        if (strlen($name) < 1) {
            $this->addError('Imię musi zostać podane.');
        } elseif (strlen($name) > 30) {
            $this->addError('Imię może zawierać maksymalnie 30 znaków.');
        }
        if (strlen($surname) < 1) {
            $this->addError('Nazwisko musi zostać podane.');
        } elseif (strlen($surname) > 50) {
            $this->addError('Nazwisko może zawierać maksymalnie 50 znaków.');
        }
        if (strlen($newEmail) > 100 || strlen($repeatEmail) > 100) {
            $this->addError('E-mail może zawierać maksymalnie 100 znaków.');
        }
        $pregMatch = preg_match(
            '/^([0-9A-Za-z._-]+)@([0-9A-Za-z-]+\.)+([0-9A-Za-z]{1,63})$/',
            $newEmail
        );
        if ($newEmail != '' && !$pregMatch) {
            $this->addError(
                'E-mail musi mieć format zapisu: nazwisko@domena.pl'
            );
        } elseif ($repeatEmail != '') {
            $pregMatch = preg_match(
                '/^([0-9A-Za-z._-]+)@([0-9A-Za-z-]+\.)+([0-9A-Za-z]{1,63})$/',
                $repeatEmail
            );
            if (!$pregMatch) {
                $this->addError(
                    'E-mail musi mieć format zapisu: nazwisko@domena.pl'
                );
            }
        }
        if ($newEmail != $repeatEmail) {
            $this->addError('Nowy e-mail i powtórzony e-mail nie są zgodne.');
        }
        if ($email != '' && $email == $newEmail) {
            $this->addError('Nowy e-mail i stary e-mail nie mogą być zgodne.');
        }
        $http = substr($www, 0, 7) == 'http://';
        $https = substr($www, 0, 8) == 'https://';
        if ($www != '' && !$http && !$https) {
            $this->addError(
                'Strona www musi rozpoczynać się od znaków: http://'
            );
        }
        if (strlen($www) > 100) {
            $this->addError(
                'Strona www może zawierać maksymalnie 100 znaków.'
            );
        }
        if ($token != $this->csrfToken->receiveToken()) {
            $this->addError('Nieprawidłowy token przesyłanych danych.');
        }
    }
}
