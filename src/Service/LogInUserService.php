<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\UserRepository;

class LogInUserService
{
    protected object $logInUserController;
    protected object $config;
    protected object $mail;
    protected object $html;
    protected object $csrfToken;
    protected object $logInUserValidator;

    public function __construct(
        object $logInUserController,
        object $config,
        object $mail,
        object $html,
        object $csrfToken,
        object $logInUserValidator
    ) {
        $this->logInUserController = $logInUserController;
        $this->config = $config;
        $this->mail = $mail;
        $this->html = $html;
        $this->csrfToken = $csrfToken;
        $this->logInUserValidator = $logInUserValidator;
    }

    public function variableAction(
        string $login,
        string $password,
        bool $remember,
        bool $submit,
        string $token
    ): array {
        $rm = $this->logInUserController->getManager();

        if ($submit) {
            $this->logInUserValidator->validate($login, $password, $token);
            if ($this->logInUserValidator->isValid()) {
                $loginUserData = $rm->getRepository(UserRepository::class)
                    ->getLoginUserData($login);
                $passwordVerify = password_verify(
                    $password,
                    $loginUserData['user_password'] ?? ''
                );
                if ($passwordVerify) {
                    if (!$loginUserData['user_active']) {
                        $activationEmail = $this->sendActivationEmail(
                            $loginUserData['user_email'],
                            $login,
                            $loginUserData['user_key']
                        );

                        return array(
                            'content' => 'src/View/log-in-user/'
                                . 'account-not-active-info.php',
                            'activeMenu' => 'log-in-user',
                            'title' => 'Informacja',
                            'activationEmail' => $activationEmail
                        );
                    }
                    $rm->getRepository(UserRepository::class)->setUserLoged(
                        $loginUserData['user_id'],
                        $this->config->getRemoteAddress(),
                        $this->config->getDateTimeNow()
                    );
                    $_SESSION['id'] = $loginUserData['user_id'];
                    $_SESSION['admin'] = $loginUserData['user_admin'];
                    $_SESSION['user'] = $login;
                    if ($remember) {
                        setcookie(
                            'cookie_login',
                            $login . ';' . $loginUserData['user_password'],
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
