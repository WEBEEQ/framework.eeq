<?php

declare(strict_types=1);

namespace App\Validator;

use App\Bundle\Error;

class EditSiteValidator extends Error
{
    protected object $csrfToken;

    public function __construct(object $csrfToken)
    {
        parent::__construct();
        $this->csrfToken = $csrfToken;
    }

    public function validate(string $name, string $token): void
    {
        if (strlen($name) < 1) {
            $this->addError('Nazwa strony www musi zostać podana.');
        } elseif (strlen($name) > 100) {
            $this->addError(
                'Nazwa strony www może zawierać maksymalnie 100 znaków.'
            );
        }
        if ($token != $this->csrfToken->receiveToken()) {
            $this->addError('Nieprawidłowy token przesyłanych danych.');
        }
    }
}
