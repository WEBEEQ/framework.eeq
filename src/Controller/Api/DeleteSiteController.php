<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Model\Api\DeleteSiteModel;
use App\Service\Api\DeleteSiteService;
use App\Validator\Api\DeleteSiteValidator;

class DeleteSiteController
{
    public function deleteSiteAction(
        string $user,
        string $password,
        int $site
    ): array {
        $deleteSiteModel = new DeleteSiteModel();
        $deleteSiteValidator = new DeleteSiteValidator($deleteSiteModel);

        $deleteSiteModel->dbConnect();

        $deleteSiteService = new DeleteSiteService(
            $deleteSiteModel,
            $deleteSiteValidator
        );
        $message = $deleteSiteService->deleteSiteMessage(
            $user,
            $password,
            $site
        );

        $deleteSiteModel->dbClose();

        return array(
            'message' => $message->getStrMessage(),
            'success' => $message->getOk()
        );
    }
}
