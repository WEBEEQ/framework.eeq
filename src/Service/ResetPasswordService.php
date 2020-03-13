<?php declare(strict_types=1);

// src/Service/ResetPasswordService.php
namespace App\Service;

class ResetPasswordService
{
    protected $config;
    protected $mail;
    protected $html;
    protected $csrfToken;
    protected $resetPasswordModel;
    protected $resetPasswordValidator;

    public function __construct(
        object $config,
        object $mail,
        object $html,
        object $csrfToken,
        object $resetPasswordModel,
        object $resetPasswordValidator
    ) {
        $this->config = $config;
        $this->mail = $mail;
        $this->html = $html;
        $this->csrfToken = $csrfToken;
        $this->resetPasswordModel = $resetPasswordModel;
        $this->resetPasswordValidator = $resetPasswordValidator;
    }

    public function variableAction(
        string $login,
        bool $submit,
        string $token
    ): array {
        if ($submit) {
            $this->resetPasswordValidator->validate($login, $token);
            if ($this->resetPasswordValidator->isValid()) {
                $userLogin = $this->resetPasswordModel->isUserLogin(
                    $login,
                    $active,
                    $email,
                    $key
                );
                if ($userLogin) {
                    if (!$active) {
                        $activationEmail = $this->sendActivationEmail(
                            $email,
                            $login,
                            $key
                        );

                        return array(
                            'layout' => 'src/Layout/main/main.php',
                            'content' => 'src/View/reset-password/'
                                . 'account-not-active-info.php',
                            'activeMenu' => 'reset-password',
                            'title' => 'Informacja',
                            'activationEmail' => $activationEmail
                        );
                    }
                    $passwordChangeEmail = $this->sendPasswordChangeEmail(
                        $email,
                        $login,
                        $key
                    );

                    return array(
                        'layout' => 'src/Layout/main/main.php',
                        'content' => 'src/View/reset-password/'
                            . 'more-instructions-info.php',
                        'activeMenu' => 'reset-password',
                        'title' => 'Informacja',
                        'passwordChangeEmail' => $passwordChangeEmail
                    );
                } else {
                    $this->resetPasswordValidator->addError(
                        'Konto o podanym loginie nie istnieje.'
                    );
                }
            }
        }

        return array(
            'layout' => 'src/Layout/main/main.php',
            'content' => 'src/View/reset-password/reset-password.php',
            'activeMenu' => 'reset-password',
            'title' => 'Resetowanie',
            'error' => $this->html->prepareError(
                $this->resetPasswordValidator->getError()
            ),
            'login' => $login,
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
                . "\r\n\r\n" . $this->config->getUrl() . '/resetowanie,'
                . $login . ',' . $key . "\r\n\r\n" . '--' . "\r\n"
                . $this->config->getAdminEmail()
        );
    }
}
