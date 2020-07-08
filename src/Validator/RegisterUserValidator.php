<?php

declare(strict_types=1);

namespace App\Validator;

use App\Bundle\Error;
use App\Repository\UserRepository;

class RegisterUserValidator extends Error
{
    protected object $csrfToken;
    protected object $rm;

    public function __construct(object $csrfToken, object $rm)
    {
        parent::__construct();
        $this->csrfToken = $csrfToken;
        $this->rm = $rm;
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
        if (strlen($login) < 3) {
            $this->addError('Login musi zawierać minimalnie 3 znaki.');
        } elseif (strlen($login) > 20) {
            $this->addError('Login może zawierać maksymalnie 20 znaków.');
        }
        if (!preg_match('/^([0-9A-Za-z]*)$/', $login)) {
            $this->addError('Login może składać się tylko z liter i cyfr.');
        }
        if (
            $login !== ''
            && $this->rm->getRepository(UserRepository::class)
                ->isUserLogin($login)
        ) {
            $this->addError('Konto o podanym loginie już istnieje.');
        }
        if (strlen($password) < 8 || strlen($repeatPassword) < 8) {
            $this->addError('Hasło musi zawierać minimalnie 8 znaków.');
        } elseif (strlen($password) > 30 || strlen($repeatPassword) > 30) {
            $this->addError('Hasło może zawierać maksymalnie 30 znaków.');
        }
        if (!preg_match('/^([!@#$%^&*()0-9A-Za-z]*)$/', $password)) {
            $this->addError('Hasło może składać się tylko z liter i cyfr.');
        } elseif (
            !preg_match(
                '/^([!@#$%^&*()0-9A-Za-z]*)$/',
                $repeatPassword
            )
        ) {
            $this->addError(
                'Hasło może składać się tylko z liter i cyfr.'
            );
        }
        if ($password !== $repeatPassword) {
            $this->addError('Hasło i powtórzone hasło nie są zgodne.');
        }
        if (strlen($email) > 100 || strlen($repeatEmail) > 100) {
            $this->addError('E-mail może zawierać maksymalnie 100 znaków.');
        }
        if (
            !preg_match(
                '/^([0-9A-Za-z._-]+)@([0-9A-Za-z-]+\.)+([0-9A-Za-z]{1,63})$/',
                $email
            )
        ) {
            $this->addError(
                'E-mail musi mieć format zapisu: nazwisko@domena.pl'
            );
        } elseif (
            !preg_match(
                '/^([0-9A-Za-z._-]+)@([0-9A-Za-z-]+\.)+([0-9A-Za-z]{1,63})$/',
                $repeatEmail
            )
        ) {
            $this->addError(
                'E-mail musi mieć format zapisu: nazwisko@domena.pl'
            );
        }
        if ($email !== $repeatEmail) {
            $this->addError('E-mail i powtórzony e-mail nie są zgodne.');
        }
        if (!$accept) {
            $this->addError('Musisz zaakceptować regulamin serwisu.');
        }
        if ($token !== $this->csrfToken->receiveToken()) {
            $this->addError('Nieprawidłowy token przesyłanych danych.');
        }
    }
}
