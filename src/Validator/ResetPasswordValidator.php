<?php

declare(strict_types=1);

namespace App\Validator;

use App\Bundle\Error;

class ResetPasswordValidator extends Error
{
    protected object $csrfToken;

    public function __construct(object $csrfToken)
    {
        parent::__construct();
        $this->csrfToken = $csrfToken;
    }

    public function validate(string $login, string $token): void
    {
        if ($login == '') {
            $this->addError('Podaj login twojego konta.');
        }
        if ($token != $this->csrfToken->receiveToken()) {
            $this->addError('Nieprawidłowy token przesyłanych danych.');
        }
    }
}
