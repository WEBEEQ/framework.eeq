<?php

declare(strict_types=1);

// src/Validator/UserAccountValidator.php
namespace App\Validator;

use App\Bundle\Error;

class UserAccountValidator extends Error
{
    protected $csrfToken;

    public function __construct(object $csrfToken)
    {
        parent::__construct();
        $this->csrfToken = $csrfToken;
    }

    public function validate(string $name, string $www, string $token): void
    {
        if (strlen($name) < 1) {
            $this->addError('Nazwa strony www musi zostać podana.');
        } elseif (strlen($name) > 100) {
            $this->addError(
                'Nazwa strony www może zawierać maksymalnie 100 znaków.'
            );
        }
        $http = substr($www, 0, 7) == 'http://';
        $https = substr($www, 0, 8) == 'https://';
        if (!$http && !$https) {
            $this->addError('Url musi rozpoczynać się od znaków: http://');
        }
        if (strlen($www) > 100) {
            $this->addError('Url może zawierać maksymalnie 100 znaków.');
        }
        if ($token != $this->csrfToken->receiveToken()) {
            $this->addError('Nieprawidłowy token przesyłanych danych.');
        }
    }
}
