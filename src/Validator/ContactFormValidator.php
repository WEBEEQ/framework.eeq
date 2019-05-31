<?php declare(strict_types=1);

// src/Validator/ContactFormValidator.php
namespace App\Validator;

use App\Bundle\Error;

class ContactFormValidator extends Error
{
    protected $csrfToken;

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
        $error = '';

        $pregMatch = preg_match(
            '/^([0-9A-Za-z._-]+)@([0-9A-Za-z-]+\.)+([0-9A-Za-z]{1,63})$/',
            $email
        );
        if (!$pregMatch) {
            $error .= 'E-mail musi mieć format zapisu: '
                . 'nazwisko@domena.pl' . "\r\n";
        }
        if ($subject == '') {
            $error .= 'Temat wiadomości musi zostać podany.' . "\r\n";
        }
        if ($text == '') {
            $error .= 'Treść wiadomości musi zostać podana.' . "\r\n";
        }
        if ($token != $this->csrfToken->receiveToken()) {
            $error .= 'Nieprawidłowy token przesyłanych danych.' . "\r\n";
        }

        $this->setError($error);
    }
}
