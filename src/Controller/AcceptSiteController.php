<?php declare(strict_types=1);

// src/Controller/AcceptSiteController.php
namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Config, Email};
use App\Model\AcceptSiteModel;

class AcceptSiteController
{
    protected $serverName;
    protected $serverDomain;
    protected $administratorEmail;

    public function __construct()
    {
        $this->serverName = Config::getServerName();
        $this->serverDomain = Config::getServerDomain();
        $this->administratorEmail = Config::getAdministratorEmail();
    }

    public function acceptSiteAction(
        string $url,
        string $name,
        string $www,
        int $active,
        int $visible,
        bool $delete,
        bool $submit,
        int $site,
        string $remoteAddress,
        string $date
    ): array {
        $message = '';
        $ok = false;

        $acceptSiteModel = new AcceptSiteModel();
        $acceptSiteModel->dbConnect();

        if (!$acceptSiteModel->isSiteId($site)) {
            $acceptSiteModel->dbClose();
            header('Location: ' . $url . '/logowanie');
            exit;
        }

        if ($submit) {
            if ($delete) {
                $acceptSiteModel->getUserData($site, $login, $email);
                if ($acceptSiteModel->deleteSiteData($site)) {
                    $message .= 'Strona www została odrzucona.' . "\r\n";
                    $message .= 'Dane strony www zostały usunięte.' . "\r\n";
                    $ok = true;
                    $acceptationEmail = $this->sendAcceptationEmail(
                        $active,
                        $delete,
                        $email,
                        $login,
                        $www
                    );
                    if ($acceptationEmail) {
                        $message .= 'E-mail akceptacyjny został wysłany.'
                            . "\r\n";
                    } elseif ($acceptationEmail === false) {
                        $message .= "Wysłanie e-mail'a akceptacyjnego "
                            . 'nie powiodło się.' . "\r\n";
                        $ok = false;
                    }
                    $name = '';
                    $www = '';
                    $active = 0;
                    $visible = 0;
                    $delete = false;
                } else {
                    $message .= 'Usunięcie danych strony www nie powiodło się.'
                        . "\r\n";
                }
            } else {
                if (strlen($name) < 1) {
                    $message .= 'Nazwa strony www musi zostać podana.'
                        . "\r\n";
                } elseif (strlen($name) > 100) {
                    $message .= 'Nazwa strony www może zawierać maksymalnie '
                        . '100 znaków.' . "\r\n";
                }
                $http = substr($www, 0, 7) != 'http://';
                $https = substr($www, 0, 8) != 'https://';
                if ($http && $https) {
                    $message .= 'Url musi rozpoczynać się od znaków: http://'
                        . "\r\n";
                }
                if (strlen($www) > 100) {
                    $message .= 'Url może zawierać maksymalnie 100 znaków.'
                        . "\r\n";
                }
                if ($message == '') {
                    $siteData = $acceptSiteModel->setSiteData(
                        $site,
                        $active,
                        $visible,
                        $name,
                        $www,
                        $remoteAddress,
                        $date
                    );
                    if ($siteData) {
                        if ($active == 1) {
                            $message .= 'Strona www została zaakceptowana.'
                                . "\r\n";
                        }
                        $message .= 'Dane strony www zostały zapisane.'
                            . "\r\n";
                        $ok = true;
                        $acceptSiteModel->getSiteData(
                            $site,
                            $active,
                            $visible,
                            $name,
                            $www,
                            $login,
                            $email
                        );
                        $acceptationEmail = $this->sendAcceptationEmail(
                            (int) $active,
                            $delete,
                            $email,
                            $login,
                            $www
                        );
                        if ($acceptationEmail) {
                            $message .= 'E-mail akceptacyjny został wysłany.'
                                . "\r\n";
                        } elseif ($acceptationEmail === false) {
                            $message .= "Wysłanie e-mail'a akceptacyjnego "
                                . 'nie powiodło się.' . "\r\n";
                            $ok = false;
                        }
                    } else {
                        $message .= 'Zapisanie danych strony www '
                            . 'nie powiodło się.' . "\r\n";
                    }
                }
            }
        } else {
            $acceptSiteModel->getSiteData(
                $site,
                $active,
                $visible,
                $name,
                $www,
                $login,
                $email
            );
        }

        $message = Html::prepareMessage($message, $ok);

        $acceptSiteModel->dbClose();

        return array(
            'message' => $message,
            'name' => $name,
            'www' => $www,
            'active' => $active,
            'visible' => $visible,
            'delete' => $delete
        );
    }

    private function sendAcceptationEmail(
        int $active,
        bool $delete,
        string $email,
        string $login,
        string $www
    ): ?bool {
        if ($delete) {
            $accept = 'Strona www podana poniżej została odrzucona.';
        } elseif ($active == 1) {
            $accept = 'Strona www podana poniżej została zaakceptowana.';
        } else {
            return null;
        }

        return Email::sendEmail(
            $this->serverName,
            $this->administratorEmail,
            $email,
            'Akceptacja strony www konta ' . $login . ' w serwisie '
                . $this->serverDomain,
            $accept . "\r\n\r\n" . $www . "\r\n\r\n" . '--' . "\r\n"
                . $this->administratorEmail
        );
    }
}
