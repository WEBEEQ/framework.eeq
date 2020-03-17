<?php

declare(strict_types=1);

// src/Service/ActivateUserService.php
namespace App\Service;

class ActivateUserService
{
    protected $activateUserModel;

    public function __construct(object $activateUserModel)
    {
        $this->activateUserModel = $activateUserModel;
    }

    public function variableAction(string $user, string $code): array
    {
        if ($user && $code) {
            $userKey = $this->activateUserModel->getUserKey(
                $user,
                $id,
                $active
            );
            if ($code != $userKey) {
                return array(
                    'layout' => 'src/Layout/main/main.php',
                    'content' => 'src/View/activate-user/'
                        . 'code-not-valid-info.php',
                    'activeMenu' => 'activate-user',
                    'title' => 'Informacja'
                );
            }
            if ($active) {
                return array(
                    'layout' => 'src/Layout/main/main.php',
                    'content' => 'src/View/activate-user/'
                        . 'account-is-active-info.php',
                    'activeMenu' => 'activate-user',
                    'title' => 'Informacja'
                );
            }
            $key = $this->activateUserModel->generateKey();
            $userActive = $this->activateUserModel->setUserActive(
                (int) $id,
                $key
            );

            return array(
                'layout' => 'src/Layout/main/main.php',
                'content' => 'src/View/activate-user/'
                    . 'account-activation-info.php',
                'activeMenu' => 'activate-user',
                'title' => 'Informacja',
                'userActive' => $userActive
            );
        }

        return array(
            'layout' => 'src/Layout/main/main.php',
            'content' => 'src/View/activate-user/activate-user.php',
            'activeMenu' => 'activate-user',
            'title' => 'Aktywacja'
        );
    }
}
