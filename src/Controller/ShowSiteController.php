<?php declare(strict_types=1);

// src/Controller/ShowSiteController.php
namespace App\Controller;

use App\Model\ShowSiteModel;

class ShowSiteController
{
    public function showSiteAction(string $url, int $id): array
    {
        $www = '';

        $showSiteModel = new ShowSiteModel();
        $showSiteModel->dbConnect();

        if (!$showSiteModel->isUserMaxShow($id)) {
            if ($www = $showSiteModel->getSiteRandomUrl($id, $user, $show)) {
                $showSiteModel->setUserShow($id, (int) $user, (int) $show);
            } else {
                $www = $showSiteModel->getSiteRandomUrl($id, $user, $show, 0);
                if ($www) {
                    $showSiteModel->setUserShow(
                        $id,
                        (int) $user,
                        (int) $show,
                        0
                    );
                }
            }
        } else {
            $www = $url . '/info';
        }

        $showSiteModel->dbClose();

        return array('www' => $www);
    }
}
