<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\UserRepository;

class ChangePasswordService
{
    protected object $changePasswordController;
    protected object $config;
    protected object $mail;
    protected object $html;
    protected object $key;
    protected object $csrfToken;
    protected object $changePasswordValidator;

    public function __construct(
        object $changePasswordController,
        object $config,
        object $mail,
        object $html,
        object $key,
        object $csrfToken,
        object $changePasswordValidator
    ) {
        $this->changePasswordController = $changePasswordController;
        $this->config = $config;
        $this->mail = $mail;
        $this->html = $html;
        $this->key = $key;
        $this->csrfToken = $csrfToken;
        $this->changePasswordValidator = $changePasswordValidator;
    }

    public function variableAction(
        string $newPassword,
        string $repeatPassword,
        bool $submit,
        string $token,
        string $user,
        string $code
    ): array {
        $rm = $this->changePasswordController->getManager();

        if ($user && $code) {
            $passwordUserData = $rm->getRepository(UserRepository::class)
                ->getPasswordUserData($user);
            if ($code !== $passwordUserData['user_key']) {
                return array(
                    'content' => 'src/View/change-password/'
                        . 'code-not-valid-info.php',
                    'activeMenu' => 'change-password',
                    'title' => 'Informacja'
                );
            }
            if (!$passwordUserData['user_active']) {
                $activationEmail = $this->sendActivationEmail(
                    $passwordUserData['user_email'],
                    $user,
                    $code
                );

                return array(
                    'content' => 'src/View/change-password/'
                        . 'account-not-active-info.php',
                    'activeMenu' => 'change-password',
                    'title' => 'Informacja',
                    'activationEmail' => $activationEmail
                );
            }
            if ($submit) {
                $this->changePasswordValidator->validate(
                    $newPassword,
                    $repeatPassword,
                    $token
                );
                if ($this->changePasswordValidator->isValid()) {
                    $key = $this->key->generateKey();
                    $passwordUserData = $rm
                        ->getRepository(UserRepository::class)
                        ->setPasswordUserData(
                            $passwordUserData['user_id'],
                            $newPassword,
                            $key,
                            $this->config->getRemoteAddress(),
                            $this->config->getDateTimeNow()
                        );
                    if ($passwordUserData) {
                        setcookie('cookie_login', '', 0, '/');

                        return array(
                            'content' => 'src/View/change-password/'
                                . 'password-changed-info.php',
                            'activeMenu' => 'change-password',
                            'title' => 'Informacja'
                        );
                    } else {
                        return array(
                            'content' => 'src/View/change-password/'
                                . 'password-not-changed-info.php',
                            'activeMenu' => 'change-password',
                            'title' => 'Informacja'
                        );
                    }
                }
            }
        }

        return array(
            'content' => 'src/View/change-password/change-password.php',
            'activeMenu' => 'change-password',
            'title' => 'Resetowanie',
            'error' => $this->html->prepareError(
                $this->changePasswordValidator->getError()
            ),
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
