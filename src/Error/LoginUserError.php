<?php declare(strict_types=1);

// src/Error/LoginUserError.php
namespace App\Error;

use App\Bundle\Error;

class LoginUserError extends Error
{
    protected $csrfToken;

    public function __construct(object $csrfToken)
    {
        parent::__construct();
        $this->csrfToken = $csrfToken;
    }

    public function validate(
        bool $forget,
        string $login,
        string $password,
        string $token
    ): void {
        $error = '';

        if ($forget) {
            if ($login == '') {
                $error .= 'Podaj login twojego konta.' . "\r\n";
            }
        } else {
            if ($login == '' || $password == '') {
                $error .= 'Podaj login i hasło twojego konta.' . "\r\n";
            }
        }
        if ($token != $this->csrfToken->receiveToken()) {
            $error .= 'Nieprawidłowy token przesyłanych danych.' . "\r\n";
        }

        $this->setError($error);
    }
}
