<?php declare(strict_types=1);

// src/Controller/EditSiteController.php
namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Config, Token};
use App\Model\EditSiteModel;
use App\Service\EditSiteService;
use App\Validator\EditSiteValidator;

class EditSiteController
{
    public function editSiteAction(
        string $name,
        string $www,
        int $visible,
        bool $delete,
        bool $submit,
        string $token,
        int $site,
        int $id
    ): array {
        $config = new Config();
        $html = new Html();
        $csrfToken = new Token();
        $editSiteModel = new EditSiteModel();
        $editSiteValidator = new EditSiteValidator($csrfToken);

        $editSiteModel->dbConnect();

        if (!$editSiteModel->isUserSiteId($id, $site)) {
            $editSiteModel->dbClose();
            header('Location: ' . $config->getUrl() . '/logowanie');
            exit;
        }

        $editSiteService = new EditSiteService(
            $config,
            $html,
            $csrfToken,
            $editSiteModel,
            $editSiteValidator
        );
        $array = $editSiteService->variableAction(
            $name,
            $www,
            $visible,
            $delete,
            $submit,
            $token,
            $site
        );

        $editSiteModel->dbClose();

        return $array;
    }
}
