<?php declare(strict_types=1);

// src/Service/AddUserService.php
namespace App\Service;

class AddUserService
{
    protected $config;
    protected $mail;
    protected $html;
    protected $csrfToken;
    protected $addUserModel;
    protected $addUserValidator;

    public function __construct(
        object $config,
        object $mail,
        object $html,
        object $csrfToken,
        object $addUserModel,
        object $addUserValidator
    ) {
        $this->config = $config;
        $this->mail = $mail;
        $this->html = $html;
        $this->csrfToken = $csrfToken;
        $this->addUserModel = $addUserModel;
        $this->addUserValidator = $addUserValidator;
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
        string $token,
        string $user,
        string $code
    ): array {
        if ($submit) {
            $this->addUserValidator->validate(
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
            if ($this->addUserValidator->isValid()) {
                $key = $this->addUserModel->generateKey();
                $userData = $this->addUserModel->addUserData(
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
                        'content' =>
                            'src/View/add-user/account-created-info.php',
                        'activeMenu' => 'add-user',
                        'title' => 'Informacja',
                        'activationEmail' => $activationEmail
                    );
                } else {
                    return array(
                        'layout' => 'src/Layout/main/main.php',
                        'content' =>
                            'src/View/add-user/account-not-created-info.php',
                        'activeMenu' => 'add-user',
                        'title' => 'Informacja'
                    );
                }
            }
        } elseif ($user && $code) {
            $userKey = $this->addUserModel->getUserKey(
                $user,
                $id,
                $active
            );
            if ($code == $userKey) {
                if ($active) {
                    return array(
                        'layout' => 'src/Layout/main/main.php',
                        'content' =>
                            'src/View/add-user/account-is-active-info.php',
                        'activeMenu' => 'add-user',
                        'title' => 'Informacja'
                    );
                } else {
                    $userActive = $this->addUserModel->setUserActive(
                        (int) $id
                    );

                    return array(
                        'layout' => 'src/Layout/main/main.php',
                        'content' =>
                            'src/View/add-user/account-activation-info.php',
                        'activeMenu' => 'add-user',
                        'title' => 'Informacja',
                        'userActive' => $userActive
                    );
                }
            } else {
                return array(
                    'layout' => 'src/Layout/main/main.php',
                    'content' => 'src/View/add-user/code-not-valid-info.php',
                    'activeMenu' => 'add-user',
                    'title' => 'Informacja'
                );
            }
        }

        return array(
            'layout' => 'src/Layout/main/main.php',
            'content' => 'src/View/add-user/add-user.php',
            'activeMenu' => 'add-user',
            'title' => 'Rejestracja',
            'error' => $this->html->prepareError(
                $this->addUserValidator->getError()
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
                . "\r\n\r\n" . $this->config->getUrl() . '/rejestracja,'
                . $login . ',' . $key . "\r\n\r\n" . '--' . "\r\n"
                . $this->config->getAdminEmail()
        );
    }
}
