<?php

declare(strict_types=1);

namespace App\Service;

class ChangePasswordService
{
    protected object $config;
    protected object $mail;
    protected object $html;
    protected object $csrfToken;
    protected object $changePasswordModel;
    protected object $changePasswordValidator;

    public function __construct(
        object $config,
        object $mail,
        object $html,
        object $csrfToken,
        object $changePasswordModel,
        object $changePasswordValidator
    ) {
        $this->config = $config;
        $this->mail = $mail;
        $this->html = $html;
        $this->csrfToken = $csrfToken;
        $this->changePasswordModel = $changePasswordModel;
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
        if ($user && $code) {
            $userKey = $this->changePasswordModel->getUserKey(
                $user,
                $id,
                $active,
                $email
            );
            if ($code != $userKey) {
                return array(
                    'layout' => 'src/Layout/main/main.php',
                    'content' => 'src/View/change-password/'
                        . 'code-not-valid-info.php',
                    'activeMenu' => 'change-password',
                    'title' => 'Informacja'
                );
            }
            if (!$active) {
                $activationEmail = $this->sendActivationEmail(
                    $email,
                    $user,
                    $code
                );

                return array(
                    'layout' => 'src/Layout/main/main.php',
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
                    $key = $this->changePasswordModel->generateKey();
                    $userPassword = $this->changePasswordModel
                        ->setUserPassword(
                            (int) $id,
                            $newPassword,
                            $key,
                            $this->config->getRemoteAddress(),
                            $this->config->getDateTimeNow()
                        );
                    if ($userPassword) {
                        setcookie('login', '', 0, '/');

                        return array(
                            'layout' => 'src/Layout/main/main.php',
                            'content' => 'src/View/change-password/'
                                . 'password-changed-info.php',
                            'activeMenu' => 'change-password',
                            'title' => 'Informacja'
                        );
                    } else {
                        return array(
                            'layout' => 'src/Layout/main/main.php',
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
            'layout' => 'src/Layout/main/main.php',
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
