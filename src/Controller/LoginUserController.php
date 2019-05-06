<?php declare(strict_types=1);

// src/Controller/LoginUserController.php
namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Config, Email};
use App\Model\LoginUserModel;

class LoginUserController
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

    public function loginUserAction(
        string $login,
        string $password,
        bool $forget,
        bool $remember,
        bool $submit,
        string $user,
        string $code,
        string $remoteAddress,
        string $date
    ): array {
        $message = '';
        $ok = false;

        $loginUserModel = new LoginUserModel();
        $loginUserModel->dbConnect();

        if ($submit) {
            if ($forget) {
                if ($login == '') {
                    $message .= 'Podaj login twojego konta.' . "\r\n";
                } else {
                    $userLogin = $loginUserModel->isUserLogin(
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
                            if ($passwordChangeEmail) {
                                $message .= 'Sprawdź pocztę w celu poznania '
                                    . 'dalszych instrukcji.' . "\r\n";
                                $ok = true;
                                $login = '';
                                $password = '';
                                $forget = 0;
                                $remember = 0;
                            } else {
                                $message .= "Wysłanie e-mail'a z dalszymi "
                                    . 'instrukcjami nie powiodło się.' . "\r\n";
                            }
                        } else {
                            $message .= 'Konto o podanym loginie nie jest '
                                . 'aktywne.' . "\r\n";
                            $activationEmail = $this->sendActivationEmail(
                                $email,
                                $login,
                                $key
                            );
                            if ($activationEmail) {
                                $message .= 'Sprawdź pocztę w celu aktywacji '
                                    . 'konta.' . "\r\n";
                            } else {
                                $message .= "Wysłanie e-mail'a aktywacyjnego "
                                    . 'nie powiodło się.' . "\r\n";
                            }
                        }
                    } else {
                        $message .= 'Konto o podanym loginie nie istnieje.'
                            . "\r\n";
                    }
                }
            } else {
                if ($login == '' || $password == '') {
                    $message .= 'Podaj login i hasło twojego konta.' . "\r\n";
                } else {
                    $userPassword = $loginUserModel->isUserPassword(
                        $login,
                        $password,
                        $id,
                        $admin,
                        $active,
                        $passwordCode,
                        $email,
                        $key
                    );
                    if ($userPassword) {
                        if ($active) {
                            $loginUserModel->setUserLoged(
                                (int) $id,
                                $remoteAddress,
                                $date
                            );
                            $_SESSION['id'] = $id;
                            $_SESSION['admin'] = $admin;
                            $_SESSION['user'] = $login;
                            if ($remember) {
                                setcookie(
                                    'login',
                                    $login . ';' . $passwordCode,
                                    time() + 365 * 24 * 60 * 60, '/'
                                );
                            }
                            header('Location: ' . $url . '/konto');
                            exit;
                        } else {
                            $message .= 'Konto o podanym loginie i haśle '
                                . 'nie jest aktywne.' . "\r\n";
                            $activationEmail = $this->sendActivationEmail(
                                $email,
                                $login,
                                $key
                            );
                            if ($activationEmail) {
                                $message .= 'Sprawdź pocztę w celu aktywacji '
                                    . 'konta.' . "\r\n";
                            } else {
                                $message .= "Wysłanie e-mail'a aktywacyjnego "
                                    . 'nie powiodło się.' . "\r\n";
                            }
                        }
                    } else {
                        $message .= 'Konto o podanym loginie i haśle '
                            . 'nie istnieje.' . "\r\n";
                    }
                }
            }
        } elseif ($user && $code) {
            $userKey = $loginUserModel->getUserKey(
                $user,
                $id,
                $active,
                $email
            );
            if ($code == $userKey) {
                if (!$active) {
                    $message .= 'Konto użytkownika nie jest aktywne.' . "\r\n";
                } else {
                    $password = $loginUserModel->generatePassword();
                    $key = $loginUserModel->generateKey();
                    $userPassword = $loginUserModel->setUserPassword(
                        (int) $id,
                        $password,
                        $key,
                        $remoteAddress,
                        $date
                    );
                    if ($userPassword) {
                        $message .= 'Hasło konta użytkownika zostało '
                            . 'zmienione.' . "\r\n";
                        $ok = true;
                        $newPasswordEmail = $this->sendNewPasswordEmail(
                            $email,
                            $user,
                            $password
                        );
                        if ($newPasswordEmail) {
                            $message .= 'Sprawdź pocztę w celu zapoznania '
                                . 'z hasłem.' . "\r\n";
                        } else {
                            $message .= "Wysłanie e-mail'a z hasłem "
                                . 'nie powiodło się.' . "\r\n";
                            $ok = false;
                        }
                        $login = '';
                        $password = '';
                        $forget = false;
                        $remember = false;
                    } else {
                        $message .= 'Zmiana hasła konta użytkownika '
                            . 'nie powiodła się.' . "\r\n";
                    }
                }
            } else {
                $message .= 'Podany kod zmiany hasła jest niepoprawny.'
                    . "\r\n";
            }
        }

        $message = Html::prepareMessage($message, $ok);

        $loginUserModel->dbClose();

        return array(
            'message' => $message,
            'login' => $login,
            'forget' => $forget,
            'remember' => $remember
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

    private function sendPasswordChangeEmail(
        string $email,
        string $login,
        string $key
    ): bool {
        return Email::sendEmail(
            $this->serverName,
            $this->administratorEmail,
            $email,
            'Zmiana hasła konta ' . $login . ' w serwisie '
                . $this->serverDomain,
            'Aby zmienić hasło konta, otwórz w oknie przeglądarki url poniżej.'
                . "\r\n\r\n" . $this->url . '/logowanie,' . $login . ',' . $key
                . "\r\n\r\n" . '--' . "\r\n" . $this->administratorEmail
        );
    }

    private function sendNewPasswordEmail(
        string $email,
        string $login,
        string $password
    ): bool {
        return Email::sendEmail(
            $this->serverName,
            $this->administratorEmail,
            $email,
            'Nowe hasło konta ' . $login . ' w serwisie '
                . $this->serverDomain,
            'Nowe hasło konta: ' . $password . "\r\n\r\n" . '--' . "\r\n"
                . $this->administratorEmail
        );
    }
}
