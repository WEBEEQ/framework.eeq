<?php declare(strict_types=1);

// src/Controller/EditUserController.php
namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Config, Email};
use App\Model\EditUserModel;

class EditUserController
{
    protected $url;
    protected $serverName;
    protected $serverDomain;
    protected $administratorEmail;

    public function __construct()
    {
        $this->url = Config::getUrl();
        $this->serverName = Config::getServerName();
        $this->serverDomain = Config::getServerDomain();
        $this->administratorEmail = Config::getAdministratorEmail();
    }

    public function editUserAction(
        string $url,
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
        int $user,
        string $remoteAddress,
        string $date,
        int $id,
        string $login
    ): array {
        $message = '';
        $ok = false;

        $editUserModel = new EditUserModel();
        $editUserModel->dbConnect();

        if (!$editUserModel->isUserId($id, $user)) {
            $editUserModel->dbClose();
            header('Location: ' . $url . '/logowanie');
            exit;
        }

        if ($submit) {
            if ($newPassword != '' && strlen($newPassword) < 8) {
                $message .= 'Hasło musi zawierać minimalnie 8 znaków.'
                    . "\r\n";
            } elseif ($repeatPassword != '' && strlen($repeatPassword) < 8) {
                $message .= 'Hasło musi zawierać minimalnie 8 znaków.'
                    . "\r\n";
            } else {
                $newPasswordStrlen = strlen($newPassword) > 30;
                $repeatPasswordStrlen = strlen($repeatPassword) > 30;
                if ($newPasswordStrlen || $repeatPasswordStrlen) {
                    $message .= 'Hasło może zawierać maksymalnie 30 znaków.'
                        . "\r\n";
                }
            }
            if (!preg_match('/^([!@#$%^&*()0-9A-Za-z]*)$/', $newPassword)) {
                $message .= 'Hasło może składać się tylko z liter i cyfr.'
                    . "\r\n";
            } else {
                $pregMatch = preg_match(
                    '/^([!@#$%^&*()0-9A-Za-z]*)$/',
                    $repeatPassword
                );
                if (!$pregMatch) {
                    $message .= 'Hasło może składać się tylko z liter i cyfr.'
                        . "\r\n";
                }
            }
            $userPassword = $editUserModel->isUserPassword($user, $password);
            if ($password != '' && !$userPassword) {
                $message .= 'Stare hasło nie jest zgodne z dotychczas '
                    . 'istniejącym.' . "\r\n";
            }
            $pass = ($newPassword != '' || $repeatPassword != '');
            if ($password == '' && $pass && $newPassword == $repeatPassword) {
                $message .= 'Stare hasło nie zostało podane.' . "\r\n";
            }
            $pass = ($newPassword == '' || $repeatPassword == '');
            if ($password != '' && $pass) {
                $message .= 'Nowe hasło lub powtórzone hasło '
                    . 'nie zostało podane.' . "\r\n";
            }
            if ($newPassword != $repeatPassword) {
                $message .= 'Nowe hasło i powtórzone hasło nie są zgodne.'
                    . "\r\n";
            }
            if ($password != '' && $password == $newPassword) {
                $message .= 'Nowe hasło i stare hasło nie mogą być zgodne.'
                    . "\r\n";
            }
            if (strlen($name) < 1) {
                $message .= 'Imię musi zostać podane.' . "\r\n";
            } elseif (strlen($name) > 50) {
                $message .= 'Imię może zawierać maksymalnie 50 znaków.'
                    . "\r\n";
            }
            if (strlen($surname) < 1) {
                $message .= 'Nazwisko musi zostać podane.' . "\r\n";
            } elseif (strlen($surname) > 100) {
                $message .= 'Nazwisko może zawierać maksymalnie 100 znaków.'
                    . "\r\n";
            }
            if (strlen($newEmail) > 100 || strlen($repeatEmail) > 100) {
                $message .= 'E-mail może zawierać maksymalnie 100 znaków.'
                    . "\r\n";
            }
            $pregMatch = preg_match(
                '/^([0-9A-Za-z._-]+)@([0-9A-Za-z-]+\.)+([A-Za-z]{2,4})$/',
                $newEmail
            );
            if ($newEmail != '' && !$pregMatch) {
                $message .= 'E-mail musi mieć format zapisu: '
                    . 'nazwisko@domena.pl' . "\r\n";
            } elseif ($repeatEmail != '') {
                $pregMatch = preg_match(
                    '/^([0-9A-Za-z._-]+)@([0-9A-Za-z-]+\.)+([A-Za-z]{2,4})$/',
                    $repeatEmail
                );
                if (!$pregMatch) {
                    $message .= 'E-mail musi mieć format zapisu: '
                        . 'nazwisko@domena.pl' . "\r\n";
                }
            }
            if ($newEmail != $repeatEmail) {
                $message .= 'Nowy e-mail i powtórzony e-mail nie są zgodne.'
                    . "\r\n";
            }
            if ($email != '' && $email == $newEmail) {
                $message .= 'Nowy e-mail i stary e-mail nie mogą być zgodne.'
                    . "\r\n";
            }
            $http = substr($www, 0, 7) != 'http://';
            $https = substr($www, 0, 8) != 'https://';
            if ($www != '' && $http && $https) {
                $message .= 'Strona www musi rozpoczynać się od znaków: '
                    . 'http://' . "\r\n";
            }
            if (strlen($www) > 100) {
                $message .= 'Strona www może zawierać maksymalnie '
                    . '100 znaków.' . "\r\n";
            }
            if ($login != $lastLogin) {
                $message = 'Powstrzymano próbę zapisu danych z innego konta.'
                    . "\r\n";
                $editUserModel->getUserData(
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
            } elseif ($message == '') {
                $key = $editUserModel->generateKey();
                $userData = $editUserModel->setUserData(
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
                    $remoteAddress,
                    $date
                );
                if ($userData) {
                    $message .= 'Dane użytkownika zostały zapisane.' . "\r\n";
                    $ok = true;
                    if ($newPassword != '') {
                        $message .= 'Hasło użytkownika zostało zapisane.'
                            . "\r\n";
                        setcookie('login', '', 0, '/');
                    }
                    if ($newEmail != '') {
                        $message .= 'E-mail użytkownika został zapisany.'
                            . "\r\n";
                        session_destroy();
                        setcookie('login', '', 0, '/');
                        $activationEmail = $this->sendActivationEmail(
                            $newEmail,
                            $login,
                            $key
                        );
                        if ($activationEmail) {
                            $message .= 'Sprawdź pocztę w celu '
                                . 'aktywacji konta.' . "\r\n";
                        } else {
                            $message .= "Wysłanie e-mail'a aktywacyjnego "
                                . 'nie powiodło się.' . "\r\n";
                            $ok = false;
                        }
                    }
                    $editUserModel->getUserData(
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
                } else {
                    $message .= 'Zapisanie danych użytkownika '
                        . 'nie powiodło się.' . "\r\n";
                }
            }
        } else {
            $editUserModel->getUserData(
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

        $provinceList = $editUserModel->getProvinceList();
        $cityList = $editUserModel->getCityList((int) $province);

        $message = Html::prepareMessage($message, $ok);

        $editUserModel->dbClose();

        return array(
            'message' => $message,
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
            'provinceList' => $provinceList,
            'cityList' => $cityList
        );
    }

    private function sendActivationEmail(
        string $email,
        string $login,
        string $key
    ): bool {
        return Email::sendEmail(
            $this->serverName,
            $this->administratorEmail,
            $email,
            'Aktywacja konta ' . $login . ' w serwisie ' . $this->serverDomain,
            'Aby aktywować konto, otwórz w oknie przeglądarki url poniżej.'
                . "\r\n\r\n" . $this->url . '/rejestracja,' . $login . ','
                . $key . "\r\n\r\n" . '--' . "\r\n" . $this->administratorEmail
        );
    }
}
