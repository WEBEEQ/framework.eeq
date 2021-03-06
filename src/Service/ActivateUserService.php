<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\UserRepository;

class ActivateUserService
{
    protected object $activateUserController;
    protected object $key;

    public function __construct(object $activateUserController, object $key)
    {
        $this->activateUserController = $activateUserController;
        $this->key = $key;
    }

    public function variableAction(string $user, string $code): array
    {
        $rm = $this->activateUserController->getManager();

        if ($user && $code) {
            $activationUserData = $rm->getRepository(UserRepository::class)
                ->getActivationUserData($user);
            if ($code !== $activationUserData['user_key']) {
                return array(
                    'content' => 'activate-user/code-not-valid-info.php',
                    'activeMenu' => 'activate-user',
                    'title' => 'Informacja'
                );
            }
            if ($activationUserData['user_active']) {
                return array(
                    'content' => 'activate-user/account-is-active-info.php',
                    'activeMenu' => 'activate-user',
                    'title' => 'Informacja'
                );
            }
            $key = $this->key->generateKey();
            $userActive = $rm->getRepository(UserRepository::class)
                ->setUserActive(
                    $activationUserData['user_id'],
                    $key
                );

            return array(
                'content' => 'activate-user/account-activation-info.php',
                'activeMenu' => 'activate-user',
                'title' => 'Informacja',
                'userActive' => $userActive
            );
        }

        return array(
            'content' => 'activate-user/activate-user.php',
            'activeMenu' => 'activate-user',
            'title' => 'Aktywacja'
        );
    }
}
