<?php declare(strict_types=1);

// src/Controller/AddUserController.php
namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Config, Email};
use App\Model\AddUserModel;

class AddUserController
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

    public function addUserAction(
        string $name,
        string $surname,
        string $login,
        string $password,
        string $repeatPassword,
        string $email,
        string $repeatEmail,
        bool $accept,
        bool $submit,
        string $user,
        string $code,
        string $remoteAddress,
        string $date
    ): array {
        $message = '';
        $ok = false;

        $addUserModel = new AddUserModel();
        $addUserModel->dbConnect();

        if ($submit) {
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
            if (strlen($login) < 3) {
                $message .= 'Login musi zawierać minimalnie 3 znaki.' . "\r\n";
            } elseif (strlen($login) > 20) {
                $message .= 'Login może zawierać maksymalnie 20 znaków.'
                    . "\r\n";
            }
            if (!preg_match('/^([0-9A-Za-z]*)$/', $login)) {
                $message .= 'Login może składać się tylko z liter i cyfr.'
                    . "\r\n";
            }
            if ($login != '' && $addUserModel->isUserLogin($login)) {
                $message .= 'Konto o podanym loginie już istnieje.' . "\r\n";
            }
            if (strlen($password) < 8 || strlen($repeatPassword) < 8) {
                $message .= 'Hasło musi zawierać minimalnie 8 znaków.'
                    . "\r\n";
            } elseif (strlen($password) > 30 || strlen($repeatPassword) > 30) {
                $message .= 'Hasło może zawierać maksymalnie 30 znaków.'
                    . "\r\n";
            }
            if (!preg_match('/^([!@#$%^&*()0-9A-Za-z]*)$/', $password)) {
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
            if ($password != $repeatPassword) {
                $message .= 'Hasło i powtórzone hasło nie są zgodne.' . "\r\n";
            }
            if (strlen($email) > 100 || strlen($repeatEmail) > 100) {
                $message .= 'E-mail może zawierać maksymalnie 100 znaków.'
                    . "\r\n";
            }
            $pregMatch = preg_match(
                '/^([0-9A-Za-z._-]+)@([0-9A-Za-z-]+\.)+([A-Za-z]{2,4})$/',
                $email
            );
            if (!$pregMatch) {
                $message .= 'E-mail musi mieć format zapisu: '
                    . 'nazwisko@domena.pl' . "\r\n";
            } else {
                $pregMatch = preg_match(
                    '/^([0-9A-Za-z._-]+)@([0-9A-Za-z-]+\.)+([A-Za-z]{2,4})$/',
                    $repeatEmail
                );
                if (!$pregMatch) {
                    $message .= 'E-mail musi mieć format zapisu: '
                        . 'nazwisko@domena.pl' . "\r\n";
                }
            }
            if ($email != $repeatEmail) {
                $message .= 'E-mail i powtórzony e-mail nie są zgodne.'
                    . "\r\n";
            }
            if (!$accept) {
                $message .= 'Musisz zaakceptować regulamin serwisu.' . "\r\n";
            }
            if ($message == '') {
                $key = $addUserModel->generateKey();
                $userData = $addUserModel->addUserData(
                    $name,
                    $surname,
                    $login,
                    $password,
                    $email,
                    $key,
                    $remoteAddress,
                    $date
                );
                if ($userData) {
                    $message .= 'Konto użytkownika zostało utworzone.'
                        . "\r\n";
                    $ok = true;
                    if ($this->sendActivationEmail($email, $login, $key)) {
                        $message .= 'Sprawdź pocztę w celu aktywacji konta.'
                            . "\r\n";
                    } else {
                        $message .= "Wysłanie e-mail'a aktywacyjnego "
                            . 'nie powiodło się.' . "\r\n";
                        $ok = false;
                    }
                    $name = '';
                    $surname = '';
                    $login = '';
                    $password = '';
                    $repeatPassword = '';
                    $email = '';
                    $repeatEmail = '';
                    $accept = false;
                } else {
                    $message .= 'Utworzenie konta użytkownika '
                        . 'nie powiodło się.' . "\r\n";
                }
            }
        } elseif ($user && $code) {
            if ($code == $addUserModel->getUserKey($user, $id, $active)) {
                if ($active) {
                    $message .= 'Konto użytkownika jest już aktywne.'
                        . "\r\n";
                } elseif ($addUserModel->setUserActive((int) $id)) {
                    $message .= 'Konto użytkownika zostało aktywowane.'
                        . "\r\n";
                    $ok = true;
                } else {
                    $message .= 'Aktywacja konta użytkownika nie powiodła się.'
                        . "\r\n";
                }
            } else {
                $message .= 'Podany kod aktywacyjny jest niepoprawny.'
                    . "\r\n";
            }
        }

        $message = Html::prepareMessage($message, $ok);

        $addUserModel->dbClose();

        return array(
            'message' => $message,
            'name' => $name,
            'surname' => $surname,
            'login' => $login,
            'email' => $email,
            'accept' => $accept
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
