<?php declare(strict_types=1);

// src/Service/RegisterUserService.php
namespace App\Service;

class RegisterUserService
{
    protected $config;
    protected $mail;
    protected $html;
    protected $csrfToken;
    protected $registerUserModel;
    protected $registerUserValidator;

    public function __construct(
        object $config,
        object $mail,
        object $html,
        object $csrfToken,
        object $registerUserModel,
        object $registerUserValidator
    ) {
        $this->config = $config;
        $this->mail = $mail;
        $this->html = $html;
        $this->csrfToken = $csrfToken;
        $this->registerUserModel = $registerUserModel;
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
                $key = $this->registerUserModel->generateKey();
                $userData = $this->registerUserModel->addUserData(
                    $name,
                    $surname,
                    $login,
                    $password,
                    $email,
                    $key,
                    $this->config->getRemoteAddress(),
                    $this->config->getDateTimeNow()
                );
                if ($userData) {
                    $activationEmail = $this->sendActivationEmail(
                        $email,
                        $login,
                        $key
                    );

                    return array(
                        'layout' => 'src/Layout/main/main.php',
                        'content' => 'src/View/register-user/'
                            . 'account-created-info.php',
                        'activeMenu' => 'register-user',
                        'title' => 'Informacja',
                        'activationEmail' => $activationEmail
                    );
                } else {
                    return array(
                        'layout' => 'src/Layout/main/main.php',
                        'content' => 'src/View/register-user/'
                            . 'account-not-created-info.php',
                        'activeMenu' => 'register-user',
                        'title' => 'Informacja'
                    );
                }
            }
        }

        return array(
            'layout' => 'src/Layout/main/main.php',
            'content' => 'src/View/register-user/register-user.php',
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
                . "\r\n\r\n" . $this->config->getUrl() . '/aktywacja,'
                . $login . ',' . $key . "\r\n\r\n" . '--' . "\r\n"
                . $this->config->getAdminEmail()
        );
    }
}