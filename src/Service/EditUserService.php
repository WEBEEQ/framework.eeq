<?php

declare(strict_types=1);

namespace App\Service;

class EditUserService
{
    protected object $config;
    protected object $mail;
    protected object $html;
    protected object $csrfToken;
    protected object $editUserModel;
    protected object $editUserValidator;

    public function __construct(
        object $config,
        object $mail,
        object $html,
        object $csrfToken,
        object $editUserModel,
        object $editUserValidator
    ) {
        $this->config = $config;
        $this->mail = $mail;
        $this->html = $html;
        $this->csrfToken = $csrfToken;
        $this->editUserModel = $editUserModel;
        $this->editUserValidator = $editUserValidator;
    }

    public function variableAction(
        string $lastLogin,
        string $password,
        string $newPassword,
        string $repeatPassword,
        string $name,
        string $surname,
        string $street,
        string $postcode,
        int $province,
        int $city,
        string $phone,
        string $email,
        string $newEmail,
        string $repeatEmail,
        string $www,
        string $description,
        bool $submit,
        string $token,
        int $user,
        string $login
    ): array {
        if ($submit) {
            $this->editUserValidator->validate(
                $password,
                $newPassword,
                $repeatPassword,
                $name,
                $surname,
                $email,
                $newEmail,
                $repeatEmail,
                $www,
                $token,
                $user
            );
            if ($this->editUserValidator->isValid()) {
                if ($login !== $lastLogin) {
                    return array(
                        'layout' => 'src/Layout/main/main.php',
                        'content' => 'src/View/edit-user/'
                            . 'record-stopped-info.php',
                        'activeMenu' => 'edit-user',
                        'title' => 'Informacja'
                    );
                }
                $key = $this->editUserModel->generateKey();
                $userData = $this->editUserModel->setUserData(
                    $user,
                    $province,
                    $city,
                    $name,
                    $surname,
                    $newPassword,
                    $key,
                    $newEmail,
                    $www,
                    $phone,
                    $street,
                    $postcode,
                    $description,
                    $this->config->getRemoteAddress(),
                    $this->config->getDateTimeNow()
                );
                if ($userData) {
                    if ($newPassword !== '') {
                        setcookie('login', '', 0, '/');
                    }
                    if ($newEmail !== '') {
                        session_destroy();
                        setcookie('login', '', 0, '/');
                        $activationEmail = $this->sendActivationEmail(
                            $newEmail,
                            $login,
                            $key
                        );
                    }

                    return array(
                        'layout' => 'src/Layout/main/main.php',
                        'content' => 'src/View/edit-user/data-saved-info.php',
                        'activeMenu' => 'edit-user',
                        'title' => 'Informacja',
                        'newPassword' => $newPassword,
                        'newEmail' => $newEmail,
                        'activationEmail' => $activationEmail ?? null
                    );
                } else {
                    return array(
                        'layout' => 'src/Layout/main/main.php',
                        'content' => 'src/View/edit-user/'
                            . 'data-not-saved-info.php',
                        'activeMenu' => 'edit-user',
                        'title' => 'Informacja'
                    );
                }
            }
        } else {
            $this->editUserModel->getUserData(
                $user,
                $province,
                $city,
                $name,
                $surname,
                $email,
                $www,
                $phone,
                $street,
                $postcode,
                $description
            );
        }

        $provinceList = $this->editUserModel->getProvinceList();
        $cityList = $this->editUserModel->getCityList((int) $province);

        return array(
            'layout' => 'src/Layout/main/main.php',
            'content' => 'src/View/edit-user/edit-user.php',
            'activeMenu' => 'edit-user',
            'title' => 'Edycja użytkownika',
            'error' => $this->html->prepareError(
                $this->editUserValidator->getError()
            ),
            'name' => $name,
            'surname' => $surname,
            'street' => $street,
            'postcode' => $postcode,
            'province' => $province,
            'city' => $city,
            'phone' => $phone,
            'email' => $email,
            'www' => $www,
            'description' => $description,
            'login' => $login,
            'token' => $this->csrfToken->generateToken(),
            'provinceList' => $provinceList,
            'cityList' => $cityList
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
