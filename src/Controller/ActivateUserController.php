<?php declare(strict_types=1);

// src/Controller/ActivateUserController.php
namespace App\Controller;

use App\Model\ActivateUserModel;
use App\Service\ActivateUserService;

class ActivateUserController
{
    public function activateUserAction(string $user, string $code): array
    {
        $activateUserModel = new ActivateUserModel();

        $activateUserModel->dbConnect();

        $activateUserService = new ActivateUserService($activateUserModel);
        $array = $activateUserService->variableAction(
            $user,
            $code
        );

        $activateUserModel->dbClose();

        return $array;
    }
}
