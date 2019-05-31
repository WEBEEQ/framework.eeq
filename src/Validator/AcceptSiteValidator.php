<?php declare(strict_types=1);

// src/Validator/AcceptSiteValidator.php
namespace App\Validator;

use App\Bundle\Error;

class AcceptSiteValidator extends Error
{
    protected $csrfToken;

    public function __construct(object $csrfToken)
    {
        parent::__construct();
        $this->csrfToken = $csrfToken;
    }

    public function validate(string $name, string $www, string $token): void
    {
        $error = '';

        if (strlen($name) < 1) {
            $error .= 'Nazwa strony www musi zostać podana.'
                . "\r\n";
        } elseif (strlen($name) > 100) {
            $error .= 'Nazwa strony www może zawierać maksymalnie '
                . '100 znaków.' . "\r\n";
        }
        $http = substr($www, 0, 7) != 'http://';
        $https = substr($www, 0, 8) != 'https://';
        if ($http && $https) {
            $error .= 'Url musi rozpoczynać się od znaków: http://'
                . "\r\n";
        }
        if (strlen($www) > 100) {
            $error .= 'Url może zawierać maksymalnie 100 znaków.'
                . "\r\n";
        }
        if ($token != $this->csrfToken->receiveToken()) {
            $error .= 'Nieprawidłowy token przesyłanych danych.' . "\r\n";
        }

        $this->setError($error);
    }
}
