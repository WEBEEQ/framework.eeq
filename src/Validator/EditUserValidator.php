<?php declare(strict_types=1);

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
        $error = '';

        if ($newPassword != '' && strlen($newPassword) < 8) {
            $error .= 'Hasło musi zawierać minimalnie 8 znaków.'
                . "\r\n";
        } elseif ($repeatPassword != '' && strlen($repeatPassword) < 8) {
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
        if ($password != '') {
            $userPassword = $this->editUserModel->getUserPassword($user);
            if (!password_verify($password, $userPassword)) {
                $error .= 'Stare hasło nie jest zgodne z dotychczas '
                    . 'istniejącym.' . "\r\n";
            }
        }
        $pass = ($newPassword != '' || $repeatPassword != '');
        if ($password == '' && $pass && $newPassword == $repeatPassword) {
            $error .= 'Stare hasło nie zostało podane.' . "\r\n";
        }
        $pass = ($newPassword == '' || $repeatPassword == '');
        if ($password != '' && $pass) {
            $error .= 'Nowe hasło lub powtórzone hasło '
                . 'nie zostało podane.' . "\r\n";
        }
        if ($newPassword != $repeatPassword) {
            $error .= 'Nowe hasło i powtórzone hasło nie są zgodne.'
                . "\r\n";
        }
        if ($password != '' && $password == $newPassword) {
            $error .= 'Nowe hasło i stare hasło nie mogą być zgodne.'
                . "\r\n";
        }
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
        if (strlen($newEmail) > 100 || strlen($repeatEmail) > 100) {
            $error .= 'E-mail może zawierać maksymalnie 100 znaków.'
                . "\r\n";
        }
        $pregMatch = preg_match(
            '/^([0-9A-Za-z._-]+)@([0-9A-Za-z-]+\.)+([0-9A-Za-z]{1,63})$/',
            $newEmail
        );
        if ($newEmail != '' && !$pregMatch) {
            $error .= 'E-mail musi mieć format zapisu: '
                . 'nazwisko@domena.pl' . "\r\n";
        } elseif ($repeatEmail != '') {
            $pregMatch = preg_match(
                '/^([0-9A-Za-z._-]+)@([0-9A-Za-z-]+\.)+([0-9A-Za-z]{1,63})$/',
                $repeatEmail
            );
            if (!$pregMatch) {
                $error .= 'E-mail musi mieć format zapisu: '
                    . 'nazwisko@domena.pl' . "\r\n";
            }
        }
        if ($newEmail != $repeatEmail) {
            $error .= 'Nowy e-mail i powtórzony e-mail nie są zgodne.'
                . "\r\n";
        }
        if ($email != '' && $email == $newEmail) {
            $error .= 'Nowy e-mail i stary e-mail nie mogą być zgodne.'
                . "\r\n";
        }
        $http = substr($www, 0, 7) == 'http://';
        $https = substr($www, 0, 8) == 'https://';
        if ($www != '' && !$http && !$https) {
            $error .= 'Strona www musi rozpoczynać się od znaków: '
                . 'http://' . "\r\n";
        }
        if (strlen($www) > 100) {
            $error .= 'Strona www może zawierać maksymalnie '
                . '100 znaków.' . "\r\n";
        }
        if ($token != $this->csrfToken->receiveToken()) {
            $error .= 'Nieprawidłowy token przesyłanych danych.' . "\r\n";
        }

        $this->setError($error);
    }
}
