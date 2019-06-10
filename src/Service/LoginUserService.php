<?php declare(strict_types=1);

// src/Service/LoginUserService.php
namespace App\Service;

class LoginUserService
{
    protected $config;
    protected $mail;
    protected $html;
    protected $csrfToken;
    protected $loginUserModel;
    protected $loginUserValidator;

    public function __construct(
        object $config,
        object $mail,
        object $html,
        object $csrfToken,
        object $loginUserModel,
        object $loginUserValidator
    ) {
        $this->config = $config;
        $this->mail = $mail;
        $this->html = $html;
        $this->csrfToken = $csrfToken;
        $this->loginUserModel = $loginUserModel;
        $this->loginUserValidator = $loginUserValidator;
    }

    public function variableAction(
        string $login,
        string $password,
        bool $remember,
        bool $submit,
        string $token
    ): array {
        if ($submit) {
            $this->loginUserValidator->validate($login, $password, $token);
            if ($this->loginUserValidator->isValid()) {
                $userPassword = $this->loginUserModel->getUserPassword(
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
                            'content' => 'src/View/login-user/'
                                . 'account-not-active-info.php',
                            'activeMenu' => 'login-user',
                            'title' => 'Informacja',
                            'activationEmail' => $activationEmail
                        );
                    }
                    $this->loginUserModel->setUserLoged(
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
                            time() + 365 * 24 * 60 * 60, '/'
                        );
                    }
                    header('Location: ' . $this->config->getUrl() . '/konto');
                    exit;
                } else {
                    $this->loginUserValidator->addError(
                        'Konto o podanym loginie i haśle nie istnieje.'
                    );
                }
            }
        }

        return array(
            'layout' => 'src/Layout/main/main.php',
            'content' => 'src/View/login-user/login-user.php',
            'activeMenu' => 'login-user',
            'title' => 'Logowanie',
            'error' => $this->html->prepareError(
                $this->loginUserValidator->getError()
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
                . "\r\n\r\n" . $this->config->getUrl() . '/aktywacja,'
                . $login . ',' . $key . "\r\n\r\n" . '--' . "\r\n"
                . $this->config->getAdminEmail()
        );
    }
}
