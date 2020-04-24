<?php

declare(strict_types=1);

namespace App\Service;

class LogInUserService
{
    protected object $config;
    protected object $mail;
    protected object $html;
    protected object $csrfToken;
    protected object $logInUserModel;
    protected object $logInUserValidator;

    public function __construct(
        object $config,
        object $mail,
        object $html,
        object $csrfToken,
        object $logInUserModel,
        object $logInUserValidator
    ) {
        $this->config = $config;
        $this->mail = $mail;
        $this->html = $html;
        $this->csrfToken = $csrfToken;
        $this->logInUserModel = $logInUserModel;
        $this->logInUserValidator = $logInUserValidator;
    }

    public function variableAction(
        string $login,
        string $password,
        bool $remember,
        bool $submit,
        string $token
    ): array {
        if ($submit) {
            $this->logInUserValidator->validate($login, $password, $token);
            if ($this->logInUserValidator->isValid()) {
                $userPassword = $this->logInUserModel->getUserPassword(
                    $login,
                    $id,
                    $admin,
                    $active,
                    $email,
                    $key
                );
                if (password_verify($password, $userPassword ?? '')) {
                    if (!$active) {
                        $activationEmail = $this->sendActivationEmail(
                            $email,
                            $login,
                            $key
                        );

                        return array(
                            'layout' => 'src/Layout/main/main.php',
                            'content' => 'src/View/log-in-user/'
                                . 'account-not-active-info.php',
                            'activeMenu' => 'log-in-user',
                            'title' => 'Informacja',
                            'activationEmail' => $activationEmail
                        );
                    }
                    $this->logInUserModel->setUserLoged(
                        (int) $id,
                        $this->config->getRemoteAddress(),
                        $this->config->getDateTimeNow()
                    );
                    $_SESSION['id'] = $id;
                    $_SESSION['admin'] = $admin;
                    $_SESSION['user'] = $login;
                    if ($remember) {
                        setcookie(
                            'login',
                            $login . ';' . $userPassword,
                            [
                                'expires' => time() + 365 * 24 * 60 * 60,
                                'path' => '/',
                                'domain' => $this->config->getServerName(),
                                'secure' => (
                                    $this->config->getServerPort() === 443
                                ) ? true : false,
                                'httponly' => true,
                                'samesite' => 'Strict'
                            ]
                        );
                    }
                    header('Location: ' . $this->config->getUrl() . '/konto');
                    exit;
                } else {
                    $this->logInUserValidator->addError(
                        'Konto o podanym loginie i haśle nie istnieje.'
                    );
                }
            }
        }

        return array(
            'layout' => 'src/Layout/main/main.php',
            'content' => 'src/View/log-in-user/log-in-user.php',
            'activeMenu' => 'log-in-user',
            'title' => 'Logowanie',
            'error' => $this->html->prepareError(
                $this->logInUserValidator->getError()
            ),
            'login' => $login,
            'remember' => $remember,
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
