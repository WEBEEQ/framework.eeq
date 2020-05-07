<?php

declare(strict_types=1);

namespace App\Controller;

use App\Bundle\Key;
use App\Model\ActivateUserModel;
use App\Service\ActivateUserService;

class ActivateUserController
{
    public function activateUserAction(string $user, string $code): array
    {
        $key = new Key();
        $activateUserModel = new ActivateUserModel();

        $activateUserModel->dbConnect();

        $activateUserService = new ActivateUserService(
            $key,
            $activateUserModel
        );
        $array = $activateUserService->variableAction(
            $user,
            $code
        );

        $activateUserModel->dbClose();

        return $array;
    }
}
