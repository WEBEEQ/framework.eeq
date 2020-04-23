<?php

declare(strict_types=1);

namespace App\Validator;

use App\Bundle\Error;

class ContactFormValidator extends Error
{
    protected object $csrfToken;

    public function __construct(object $csrfToken)
    {
        parent::__construct();
        $this->csrfToken = $csrfToken;
    }

    public function validate(
        string $email,
        string $subject,
        string $text,
        string $token
    ): void {
        $pregMatch = preg_match(
            '/^([0-9A-Za-z._-]+)@([0-9A-Za-z-]+\.)+([0-9A-Za-z]{1,63})$/',
            $email
        );
        if (!$pregMatch) {
            $this->addError(
                'E-mail musi mieć format zapisu: nazwisko@domena.pl'
            );
        }
        if ($subject == '') {
            $this->addError('Temat wiadomości musi zostać podany.');
        }
        if ($text == '') {
            $this->addError('Treść wiadomości musi zostać podana.');
        }
        if ($token != $this->csrfToken->receiveToken()) {
            $this->addError('Nieprawidłowy token przesyłanych danych.');
        }
    }
}
