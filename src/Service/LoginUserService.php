<?php declare(strict_types=1);

// src/Service/LoginUserService.php
namespace App\Service;

class LoginUserService
{
    protected $config;
    protected $mail;
    protected $html;
    protected $csrfToken;
    protected $loginUserError;
    protected $loginUserModel;

    public function __construct(
        object $config,
        object $mail,
        object $html,
        object $csrfToken,
        object $loginUserError,
        object $loginUserModel
    ) {
        $this->config = $config;
        $this->mail = $mail;
        $this->html = $html;
        $this->csrfToken = $csrfToken;
        $this->loginUserError = $loginUserError;
        $this->loginUserModel = $loginUserModel;
    }

    public function variableAction(
        string $login,
        string $password,
        bool $forget,
        bool $remember,
        bool $submit,
        string $token,
        string $user,
        string $code
    ): array {
        if ($submit) {
            if ($forget) {
                $this->loginUserError->validate(
                    $forget,
                    $login,
                    $password,
                    $token
                );
                if ($this->loginUserError->isValid()) {
                    $userLogin = $this->loginUserModel->isUserLogin(
                        $login,
                        $active,
                        $email,
                        $key
                    );
                    if ($userLogin) {
                        if ($active) {
                            $passwordChangeEmail =
                                $this->sendPasswordChangeEmail(
                                    $email,
                                    $login,
                                    $key
                                );

                            return array(
                                'layout' => 'src/Layout/main/main.php',
                                'content' => 'src/View/login-user/'
                                    . 'more-instructions-info.php',
                                'activeMenu' => 'login-user',
                                'title' => 'Informacja',
                                'passwordChangeEmail' => $passwordChangeEmail
                            );
                        } else {
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
                                'activationEmail' => $activationEmail,
                                'password' => false
                            );
                        }
                    } else {
                        $this->loginUserError->addError(
                            'Konto o podanym loginie nie istnieje.'
                        );
                    }
                }
            } else {
                $this->loginUserError->validate(
                    $forget,
                    $login,
                    $password,
                    $token
                );
                if ($this->loginUserError->isValid()) {
                    $userPassword = $this->loginUserModel->getUserPassword(
                        $login,
                        $id,
                        $admin,
                        $active,
                        $email,
                        $key
                    );
                    if (password_verify($password, $userPassword ?? '')) {
                        if ($active) {
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
                                    $login . ';' . $key,
                                    time() + 365 * 24 * 60 * 60, '/'
                                );
                            }
                            header(
                                'Location: ' . $this->config->getUrl()
                                    . '/konto'
                            );
                            exit;
                        } else {
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
                                'activationEmail' => $activationEmail,
                                'password' => true
                            );
                        }
                    } else {
                        $this->loginUserError->addError(
                            'Konto o podanym loginie i haśle nie istnieje.'
                        );
                    }
                }
            }
        } elseif ($user && $code) {
            $userKey = $this->loginUserModel->getUserKey(
                $user,
                $id,
                $active,
                $email
            );
            if ($code == $userKey) {
                if (!$active) {
                    return array(
                        'layout' => 'src/Layout/main/main.php',
                        'content' =>
                            'src/View/login-user/account-not-active-info.php',
                        'activeMenu' => 'login-user',
                        'title' => 'Informacja'
                    );
                } else {
                    $password = $this->loginUserModel->generatePassword();
                    $key = $this->loginUserModel->generateKey();
                    $userPassword = $this->loginUserModel->setUserPassword(
                        (int) $id,
                        $password,
                        $key,
                        $this->config->getRemoteAddress(),
                        $this->config->getDateTimeNow()
                    );
                    if ($userPassword) {
                        $newPasswordEmail = $this->sendNewPasswordEmail(
                            $email,
                            $user,
                            $password
                        );

                        return array(
                            'layout' => 'src/Layout/main/main.php',
                            'content' => 'src/View/login-user/'
                                . 'password-changed-info.php',
                            'activeMenu' => 'login-user',
                            'title' => 'Informacja',
                            'newPasswordEmail' => $newPasswordEmail
                        );
                    } else {
                        return array(
                            'layout' => 'src/Layout/main/main.php',
                            'content' => 'src/View/login-user/'
                                . 'password-not-changed-info.php',
                            'activeMenu' => 'login-user',
                            'title' => 'Informacja'
                        );
                    }
                }
            } else {
                return array(
                    'layout' => 'src/Layout/main/main.php',
                    'content' => 'src/View/login-user/code-not-valid-info.php',
                    'activeMenu' => 'login-user',
                    'title' => 'Informacja'
                );
            }
        }

        return array(
            'layout' => 'src/Layout/main/main.php',
            'content' => 'src/View/login-user/login-user.php',
            'activeMenu' => 'login-user',
            'title' => 'Logowanie',
            'error' => $this->html->prepareError(
                $this->loginUserError->getError()
            ),
            'login' => $login,
            'forget' => $forget,
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
                . "\r\n\r\n" . $this->config->getUrl() . '/rejestracja,'
                . $login . ',' . $key . "\r\n\r\n" . '--' . "\r\n"
                . $this->config->getAdminEmail()
        );
    }

    private function sendPasswordChangeEmail(
        string $email,
        string $login,
        string $key
    ): bool {
        return $this->mail->sendEmail(
            $this->config->getServerName(),
            $this->config->getAdminEmail(),
            $email,
            'Zmiana hasła konta ' . $login . ' w serwisie '
                . $this->config->getServerDomain(),
            'Aby zmienić hasło konta, otwórz w oknie przeglądarki url poniżej.'
                . "\r\n\r\n" . $this->config->getUrl() . '/logowanie,'
                . $login . ',' . $key . "\r\n\r\n" . '--' . "\r\n"
                . $this->config->getAdminEmail()
        );
    }

    private function sendNewPasswordEmail(
        string $email,
        string $login,
        string $password
    ): bool {
        return $this->mail->sendEmail(
            $this->config->getServerName(),
            $this->config->getAdminEmail(),
            $email,
            'Nowe hasło konta ' . $login . ' w serwisie '
                . $this->config->getServerDomain(),
            'Nowe hasło konta: ' . $password . "\r\n\r\n" . '--' . "\r\n"
                . $this->config->getAdminEmail()
        );
    }
}
