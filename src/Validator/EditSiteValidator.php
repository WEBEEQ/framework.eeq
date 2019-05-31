<?php declare(strict_types=1);

// src/Validator/EditSiteValidator.php
namespace App\Validator;

use App\Bundle\Error;

class EditSiteValidator extends Error
{
    protected $csrfToken;

    public function __construct(object $csrfToken)
    {
        parent::__construct();
        $this->csrfToken = $csrfToken;
    }

    public function validate(string $name, string $token): void
    {
        $error = '';

        if (strlen($name) < 1) {
            $error .= 'Nazwa strony www musi zostać podana.'
                . "\r\n";
        } elseif (strlen($name) > 100) {
            $error .= 'Nazwa strony www może zawierać maksymalnie '
                . '100 znaków.' . "\r\n";
        }
        if ($token != $this->csrfToken->receiveToken()) {
            $error .= 'Nieprawidłowy token przesyłanych danych.' . "\r\n";
        }

        $this->setError($error);
    }
}
