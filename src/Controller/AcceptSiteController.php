<?php declare(strict_types=1);

// src/Controller/AcceptSiteController.php
namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Config, Email, Token};
use App\Error\AcceptSiteError;
use App\Model\AcceptSiteModel;
use App\Service\AcceptSiteService;

class AcceptSiteController
{
    public function acceptSiteAction(
        string $name,
        string $www,
        int $active,
        int $visible,
        bool $delete,
        bool $submit,
        string $token,
        int $site
    ): array {
        $config = new Config();
        $mail = new Email();
        $html = new Html();
        $csrfToken = new Token();
        $acceptSiteError = new AcceptSiteError($csrfToken);
        $acceptSiteModel = new AcceptSiteModel();
        $acceptSiteModel->dbConnect();

        $acceptSiteService = new AcceptSiteService(
            $config,
            $mail,
            $html,
            $csrfToken,
            $acceptSiteError,
            $acceptSiteModel
        );
        $array = $acceptSiteService->variableAction(
            $name,
            $www,
            $active,
            $visible,
            $delete,
            $submit,
            $token,
            $site
        );

        $acceptSiteModel->dbClose();

        return $array;
    }
}
