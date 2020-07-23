<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\UserRepository;

class RegisterUserService
{
    protected object $registerUserController;
    protected object $config;
    protected object $mail;
    protected object $html;
    protected object $key;
    protected object $csrfToken;
    protected object $registerUserValidator;

    public function __construct(
        object $registerUserController,
        object $config,
        object $mail,
        object $html,
        object $key,
        object $csrfToken,
        object $registerUserValidator
    ) {
        $this->registerUserController = $registerUserController;
        $this->config = $config;
        $this->mail = $mail;
        $this->html = $html;
        $this->key = $key;
        $this->csrfToken = $csrfToken;
        $this->registerUserValidator = $registerUserValidator;
    }

    public function variableAction(
        string $name,
        string $surname,
        string $login,
        string $password,
        string $repeatPassword,
        string $email,
        string $repeatEmail,
        bool $accept,
        bool $submit,
        string $token
    ): array {
        $rm = $this->registerUserController->getManager();

        if ($submit) {
            $this->registerUserValidator->validate(
                $name,
                $surname,
                $login,
                $password,
                $repeatPassword,
                $email,
                $repeatEmail,
                $accept,
                $token
            );
            if ($this->registerUserValidator->isValid()) {
                $key = $this->key->generateKey();
                $registrationUserData = $rm
                    ->getRepository(UserRepository::class)
                    ->addRegistrationUserData(
                        $name,
                        $surname,
                        $login,
                        $password,
                        $key,
                        $email,
                        $this->config->getRemoteAddress(),
                        $this->config->getDateTimeNow()
                    );
                if ($registrationUserData) {
                    $activationEmail = $this->sendActivationEmail(
                        $email,
                        $login,
                        $key
                    );

                    return array(
                        'content' => 'register-user/'
                            . 'account-created-info.php',
                        'activeMenu' => 'register-user',
                        'title' => 'Informacja',
                        'activationEmail' => $activationEmail
                    );
                } else {
                    return array(
                        'content' => 'register-user/'
                            . 'account-not-created-info.php',
                        'activeMenu' => 'register-user',
                        'title' => 'Informacja'
                    );
                }
            }
        }

        return array(
            'content' => 'register-user/register-user.php',
            'activeMenu' => 'register-user',
            'title' => 'Rejestracja',
            'error' => $this->html->prepareError(
                $this->registerUserValidator->getError()
            ),
            'name' => $name,
            'surname' => $surname,
            'login' => $login,
            'email' => $email,
            'accept' => $accept,
            'token' => $this->csrfToken->generateToken()
        );
    }

    private function sendActivationEmail(
        string $email,
        string $login,
        string $key
    ): bool {
        return $this->mail->sendEmail(
            $this->config->getServerName(),
            $this->config->getAdminEmail(),
            $email,
            'Aktywacja konta ' . $login . ' w serwisie '
                . $this->config->getServerDomain(),
            'Aby aktywować konto, otwórz w oknie przeglądarki url poniżej.'
                . "\n\n" . $this->config->getUrl() . '/aktywacja,'
                . $login . ',' . $key . "\n\n" . '--' . "\n"
                . $this->config->getAdminEmail()
        );
    }
}
