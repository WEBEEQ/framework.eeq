<?php declare(strict_types=1);

// src/Controller/RestController.php
namespace App\Controller;

use App\Model\RestModel;

class RestController
{
    public function addSiteAction(
        string $user,
        string $password,
        string $name,
        string $www,
        string $remoteAddress,
        string $date
    ): array {
        $message = '';
        $ok = false;

        $restModel = new RestModel();
        $restModel->dbConnect();

        if ($restModel->isLoginPassword($user, $password, $id)) {
            if (strlen($name) < 1) {
                $message .= 'Nazwa strony www musi zostać podana.' . "\r\n";
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
                $siteData = $restModel->addSiteData(
                    (int) $id,
                    $name,
                    $www,
                    $remoteAddress,
                    $date
                );
                if ($siteData) {
                    $message .= 'Strona www została dodana i oczekuje '
                        . 'na akceptację.' . "\r\n";
                    $ok = true;
                } else {
                    $message .= 'Dodanie strony www nie powiodło się.'
                        . "\r\n";
                }
            }
        } else {
            $message .= 'Błędna autoryzacja przesyłanych danych.' . "\r\n";
        }

        $restModel->dbClose();

        return array('message' => $message, 'success' => $ok);
    }

    public function updateSiteAction(
        string $user,
        string $password,
        int $site,
        string $name,
        int $visible,
        string $remoteAddress,
        string $date
    ): array {
        $message = '';
        $ok = false;

        $restModel = new RestModel();
        $restModel->dbConnect();

        if ($restModel->isLoginPassword($user, $password, $id)) {
            if (!$restModel->isUserSite((int) $id, $site)) {
                $message .= 'Baza nie zawiera podanej strony dla autoryzacji.'
                    . "\r\n";
            }
            if (strlen($name) < 1) {
                $message .= 'Nazwa strony www musi zostać podana.' . "\r\n";
            } elseif (strlen($name) > 100) {
                $message .= 'Nazwa strony www może zawierać maksymalnie '
                    . '100 znaków.' . "\r\n";
            }
            if ($message == '') {
                $siteData = $restModel->setSiteData(
                    $site,
                    $visible,
                    $name,
                    $remoteAddress,
                    $date
                );
                if ($siteData) {
                    $message .= 'Dane strony www zostały zapisane.'
                        . "\r\n";
                    $ok = true;
                } else {
                    $message .= 'Zapisanie danych strony www '
                        . 'nie powiodło się.' . "\r\n";
                }
            }
        } else {
            $message .= 'Błędna autoryzacja przesyłanych danych.' . "\r\n";
        }

        $restModel->dbClose();

        return array('message' => $message, 'success' => $ok);
    }

    public function deleteSiteAction(
        string $user,
        string $password,
        int $site
    ): array {
        $message = '';
        $ok = false;

        $restModel = new RestModel();
        $restModel->dbConnect();

        if ($restModel->isLoginPassword($user, $password, $id)) {
            if (!$restModel->isUserSite((int) $id, $site)) {
                $message .= 'Baza nie zawiera podanej strony dla autoryzacji.'
                    . "\r\n";
            }
            if ($message == '') {
                if ($restModel->deleteSiteData($site)) {
                    $message .= 'Dane strony www zostały usunięte.' . "\r\n";
                    $ok = true;
                } else {
                    $message .= 'Usunięcie danych strony www nie powiodło się.'
                        . "\r\n";
                }
            }
        } else {
            $message .= 'Błędna autoryzacja przesyłanych danych.' . "\r\n";
        }

        $restModel->dbClose();

        return array('message' => $message, 'success' => $ok);
    }
}
