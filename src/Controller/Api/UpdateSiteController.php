<?php

declare(strict_types=1);

// src/Controller/Api/UpdateSiteController.php
namespace App\Controller\Api;

use App\Core\Config;
use App\Model\Api\UpdateSiteModel;
use App\Service\Api\UpdateSiteService;
use App\Validator\Api\UpdateSiteValidator;

class UpdateSiteController
{
    public function updateSiteAction(
        string $user,
        string $password,
        int $site,
        string $name,
        int $visible
    ): array {
        $config = new Config();
        $updateSiteModel = new UpdateSiteModel();
        $updateSiteValidator = new UpdateSiteValidator($updateSiteModel);

        $updateSiteModel->dbConnect();

        $updateSiteService = new UpdateSiteService(
            $config,
            $updateSiteModel,
            $updateSiteValidator
        );
        $message = $updateSiteService->updateSiteMessage(
            $user,
            $password,
            $site,
            $name,
            $visible
        );

        $updateSiteModel->dbClose();

        return array(
            'message' => $message->getStrMessage(),
            'success' => $message->getOk()
        );
    }
}
