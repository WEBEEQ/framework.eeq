<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\UserRepository;

class ResetPasswordService
{
    protected object $resetPasswordController;
    protected object $config;
    protected object $mail;
    protected object $html;
    protected object $csrfToken;
    protected object $resetPasswordValidator;

    public function __construct(
        object $resetPasswordController,
        object $config,
        object $mail,
        object $html,
        object $csrfToken,
        object $resetPasswordValidator
    ) {
        $this->resetPasswordController = $resetPasswordController;
        $this->config = $config;
        $this->mail = $mail;
        $this->html = $html;
        $this->csrfToken = $csrfToken;
        $this->resetPasswordValidator = $resetPasswordValidator;
    }

    public function variableAction(
        string $login,
        bool $submit,
        string $token
    ): array {
        $rm = $this->resetPasswordController->getManager();

        if ($submit) {
            $this->resetPasswordValidator->validate($login, $token);
            if ($this->resetPasswordValidator->isValid()) {
                $resetUserData = $rm->getRepository(UserRepository::class)
                    ->getResetUserData($login);
                if ($resetUserData) {
                    if (!$resetUserData['user_active']) {
                        $activationEmail = $this->sendActivationEmail(
                            $resetUserData['user_email'],
                            $login,
                            $resetUserData['user_key']
                        );

                        return array(
                            'content' => 'reset-password/'
                                . 'account-not-active-info.php',
                            'activeMenu' => 'reset-password',
                            'title' => 'Informacja',
                            'activationEmail' => $activationEmail
                        );
                    }
                    $passwordChangeEmail = $this->sendPasswordChangeEmail(
                        $resetUserData['user_email'],
                        $login,
                        $resetUserData['user_key']
                    );

                    return array(
                        'content' => 'reset-password/'
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
            'content' => 'reset-password/reset-password.php',
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
                . "\n\n" . $this->config->getUrl() . '/aktywacja,'
                . $login . ',' . $key . "\n\n" . '--' . "\n"
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
                . "\n\n" . $this->config->getUrl() . '/resetowanie,'
                . $login . ',' . $key . "\n\n" . '--' . "\n"
                . $this->config->getAdminEmail()
        );
    }
}
