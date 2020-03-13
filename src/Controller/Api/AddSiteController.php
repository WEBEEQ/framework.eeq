<?php declare(strict_types=1);

// src/Controller/Api/AddSiteController.php
namespace App\Controller\Api;

use App\Core\Config;
use App\Model\Api\AddSiteModel;
use App\Service\Api\AddSiteService;
use App\Validator\Api\AddSiteValidator;

class AddSiteController
{
    public function addSiteAction(
        string $user,
        string $password,
        string $name,
        string $www
    ): array {
        $config = new Config();
        $addSiteModel = new AddSiteModel();
        $addSiteValidator = new AddSiteValidator($addSiteModel);

        $addSiteModel->dbConnect();

        $addSiteService = new AddSiteService(
            $config,
            $addSiteModel,
            $addSiteValidator
        );
        $message = $addSiteService->addSiteMessage(
            $user,
            $password,
            $name,
            $www
        );

        $addSiteModel->dbClose();

        return array(
            'message' => $message->getStrMessage(),
            'success' => $message->getOk()
        );
    }
}
