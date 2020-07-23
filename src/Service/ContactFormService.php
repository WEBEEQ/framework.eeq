<?php

declare(strict_types=1);

namespace App\Service;

class ContactFormService
{
    protected object $config;
    protected object $mail;
    protected object $html;
    protected object $csrfToken;
    protected object $contactFormValidator;

    public function __construct(
        object $config,
        object $mail,
        object $html,
        object $csrfToken,
        object $contactFormValidator
    ) {
        $this->config = $config;
        $this->mail = $mail;
        $this->html = $html;
        $this->csrfToken = $csrfToken;
        $this->contactFormValidator = $contactFormValidator;
    }

    public function variableAction(
        string $email,
        string $subject,
        string $text,
        bool $submit,
        string $token
    ): array {
        if ($submit) {
            $this->contactFormValidator->validate(
                $email,
                $subject,
                $text,
                $token
            );
            if ($this->contactFormValidator->isValid()) {
                $contactEmail = $this->sendContactEmail(
                    $email,
                    $subject,
                    $text
                );

                return array(
                    'content' => 'contact-form/send-message-info.php',
                    'activeMenu' => 'contact-form',
                    'title' => 'Informacja',
                    'contactEmail' => $contactEmail
                );
            }
        }

        return array(
            'content' => 'contact-form/contact-form.php',
            'activeMenu' => 'contact-form',
            'title' => 'Kontakt',
            'error' => $this->html->prepareError(
                $this->contactFormValidator->getError()
            ),
            'email' => $email,
            'subject' => $subject,
            'text' => $text,
            'token' => $this->csrfToken->generateToken()
        );
    }

    private function sendContactEmail(
        string $email,
        string $subject,
        string $text
    ): bool {
        return $this->mail->sendEmail(
            $this->config->getServerName(),
            $email,
            $this->config->getAdminEmail(),
            $subject . ' [' . $this->config->getServerDomain() . ']',
            $text
        );
    }
}
