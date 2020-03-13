<?php declare(strict_types=1);

// src/Controller/ShowSiteController.php
namespace App\Controller;

use App\Core\Config;
use App\Model\ShowSiteModel;
use App\Service\ShowSiteService;

class ShowSiteController
{
    public function showSiteAction(int $id): array
    {
        $config = new Config();
        $showSiteModel = new ShowSiteModel();

        $showSiteModel->dbConnect();

        $showSiteService = new ShowSiteService($config, $showSiteModel);
        $array = $showSiteService->wwwAction($id);

        $showSiteModel->dbClose();

        return $array;
    }
}
