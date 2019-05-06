<?php declare(strict_types=1);

// src/Controller/RestController.php
namespace App\Controller;

use App\Bundle\Message;
use App\Core\Config;
use App\Model\RestModel;
use App\Service\RestService;

class RestController
{
    public function addSiteAction(
        string $user,
        string $password,
        string $name,
        string $www
    ): array {
        $config = new Config();
        $message = new Message();
        $restModel = new RestModel();
        $restModel->dbConnect();

        $restService = new RestService($config, $message, $restModel);
        $message = $restService->addSiteMessage(
            $user,
            $password,
            $name,
            $www
        );

        $restModel->dbClose();

        return array(
            'message' => $message->getStrMessage(),
            'success' => $message->getOk()
        );
    }

    public function updateSiteAction(
        string $user,
        string $password,
        int $site,
        string $name,
        int $visible
    ): array {
        $config = new Config();
        $message = new Message();
        $restModel = new RestModel();
        $restModel->dbConnect();

        $restService = new RestService($config, $message, $restModel);
        $message = $restService->updateSiteMessage(
            $user,
            $password,
            $site,
            $name,
            $visible
        );

        $restModel->dbClose();

        return array(
            'message' => $message->getStrMessage(),
            'success' => $message->getOk()
        );
    }

    public function deleteSiteAction(
        string $user,
        string $password,
        int $site
    ): array {
        $config = new Config();
        $message = new Message();
        $restModel = new RestModel();
        $restModel->dbConnect();

        $restService = new RestService($config, $message, $restModel);
        $message = $restService->deleteSiteMessage(
            $user,
            $password,
            $site
        );

        $restModel->dbClose();

        return array(
            'message' => $message->getStrMessage(),
            'success' => $message->getOk()
        );
    }
}
