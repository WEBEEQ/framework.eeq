<?php declare(strict_types=1);

// src/Controller/EditSiteController.php
namespace App\Controller;

use App\Bundle\Html;
use App\Core\{Config, Token};
use App\Error\EditSiteError;
use App\Model\EditSiteModel;
use App\Service\EditSiteService;

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
        $editSiteError = new EditSiteError($csrfToken);
        $editSiteModel = new EditSiteModel();
        $editSiteModel->dbConnect();

        $editSiteService = new EditSiteService(
            $config,
            $html,
            $csrfToken,
            $editSiteError,
            $editSiteModel
        );
        $array = $editSiteService->variableAction(
            $name,
            $www,
            $visible,
            $delete,
            $submit,
            $token,
            $site,
            $id
        );

        $editSiteModel->dbClose();

        return $array;
    }
}
