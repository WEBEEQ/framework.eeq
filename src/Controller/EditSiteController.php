<?php declare(strict_types=1);

// src/Controller/EditSiteController.php
namespace App\Controller;

use App\Bundle\Html;
use App\Model\EditSiteModel;

class EditSiteController
{
    public function editSiteAction(
        string $url,
        string $name,
        string $www,
        int $visible,
        bool $delete,
        bool $submit,
        int $site,
        string $remoteAddress,
        string $date,
        int $id
    ): array {
        $message = '';
        $ok = false;

        $editSiteModel = new EditSiteModel();
        $editSiteModel->dbConnect();

        if (!$editSiteModel->isUserSite($id, $site)) {
            $editSiteModel->dbClose();
            header('Location: ' . $url . '/logowanie');
            exit;
        }

        if ($submit) {
            if ($delete) {
                if ($editSiteModel->deleteSiteData($site)) {
                    $message .= 'Dane strony www zostały usunięte.' . "\r\n";
                    $ok = true;
                    $name = '';
                    $www = '';
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
                if ($message == '') {
                    $siteData = $editSiteModel->setSiteData(
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
                        $editSiteModel->getSiteData(
                            $site,
                            $visible,
                            $name,
                            $www
                        );
                    } else {
                        $message .= 'Zapisanie danych strony www '
                            . 'nie powiodło się.' . "\r\n";
                    }
                }
            }
        } else {
            $editSiteModel->getSiteData($site, $visible, $name, $www);
        }

        $message = Html::prepareMessage($message, $ok);

        $editSiteModel->dbClose();

        return array(
            'message' => $message,
            'name' => $name,
            'www' => $www,
            'visible' => $visible,
            'delete' => $delete
        );
    }
}
