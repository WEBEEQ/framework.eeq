<?php

declare(strict_types=1);

namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Config, Email, Token};
use App\Model\AcceptSiteModel;
use App\Service\AcceptSiteService;
use App\Validator\AcceptSiteValidator;

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
        $acceptSiteModel = new AcceptSiteModel();
        $acceptSiteValidator = new AcceptSiteValidator($csrfToken);

        $acceptSiteModel->dbConnect();

        if (!$acceptSiteModel->isSiteId($site)) {
            $acceptSiteModel->dbClose();
            header('Location: ' . $config->getUrl() . '/logowanie');
            exit;
        }

        $acceptSiteService = new AcceptSiteService(
            $config,
            $mail,
            $html,
            $csrfToken,
            $acceptSiteModel,
            $acceptSiteValidator
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
