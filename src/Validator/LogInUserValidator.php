<?php declare(strict_types=1);

// src/Validator/LogInUserValidator.php
namespace App\Validator;

use App\Bundle\Error;

class LogInUserValidator extends Error
{
    protected $csrfToken;

    public function __construct(object $csrfToken)
    {
        parent::__construct();
        $this->csrfToken = $csrfToken;
    }

    public function validate(
        string $login,
        string $password,
        string $token
    ): void {
        $error = '';

        if ($login == '' || $password == '') {
            $error .= 'Podaj login i hasło twojego konta.' . "\r\n";
        }
        if ($token != $this->csrfToken->receiveToken()) {
            $error .= 'Nieprawidłowy token przesyłanych danych.' . "\r\n";
        }

        $this->setError($error);
    }
}
