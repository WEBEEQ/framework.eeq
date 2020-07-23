<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\{CityRepository, ProvinceRepository, UserRepository};

class EditUserService
{
    protected object $editUserController;
    protected object $config;
    protected object $mail;
    protected object $html;
    protected object $key;
    protected object $csrfToken;
    protected object $editUserValidator;

    public function __construct(
        object $editUserController,
        object $config,
        object $mail,
        object $html,
        object $key,
        object $csrfToken,
        object $editUserValidator
    ) {
        $this->editUserController = $editUserController;
        $this->config = $config;
        $this->mail = $mail;
        $this->html = $html;
        $this->key = $key;
        $this->csrfToken = $csrfToken;
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
        $rm = $this->editUserController->getManager();

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
                $key = $this->key->generateKey();
                $editingUserData = $rm->getRepository(UserRepository::class)
                    ->setEditingUserData(
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
                if ($editingUserData) {
                    if ($newPassword !== '') {
                        setcookie(
                            'cookie_login',
                            '',
                            0,
                            '/',
                            $this->config->getServerName()
                        );
                    }
                    if ($newEmail !== '') {
                        session_destroy();
                        setcookie(
                            'cookie_login',
                            '',
                            0,
                            '/',
                            $this->config->getServerName()
                        );
                        $activationEmail = $this->sendActivationEmail(
                            $newEmail,
                            $login,
                            $key
                        );
                    }

                    return array(
                        'content' => 'edit-user/data-saved-info.php',
                        'activeMenu' => 'edit-user',
                        'title' => 'Informacja',
                        'newPassword' => $newPassword,
                        'newEmail' => $newEmail,
                        'activationEmail' => $activationEmail ?? null
                    );
                } else {
                    return array(
                        'content' => 'edit-user/data-not-saved-info.php',
                        'activeMenu' => 'edit-user',
                        'title' => 'Informacja'
                    );
                }
            }
        } else {
            $editingUserData = $rm->getRepository(UserRepository::class)
                ->getEditingUserData($user);
        }

        $provinceList = $rm->getRepository(ProvinceRepository::class)
            ->getProvinceList();
        $cityList = $rm->getRepository(CityRepository::class)
            ->getCityList($editingUserData['province_id'] ?? $province);

        return array(
            'content' => 'edit-user/edit-user.php',
            'activeMenu' => 'edit-user',
            'title' => 'Edycja użytkownika',
            'error' => $this->html->prepareError(
                $this->editUserValidator->getError()
            ),
            'name' => $editingUserData['user_name'] ?? $name,
            'surname' => $editingUserData['user_surname'] ?? $surname,
            'street' => $editingUserData['user_street'] ?? $street,
            'postcode' => $editingUserData['user_postcode'] ?? $postcode,
            'province' => $editingUserData['province_id'] ?? $province,
            'city' => $editingUserData['city_id'] ?? $city,
            'phone' => $editingUserData['user_phone'] ?? $phone,
            'email' => $editingUserData['user_email'] ?? $email,
            'www' => $editingUserData['user_url'] ?? $www,
            'description' => $editingUserData['user_description']
                ?? $description,
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
