<?php

declare(strict_types=1);

namespace App\Service;

class ActivateUserService
{
    protected object $key;
    protected object $activateUserModel;

    public function __construct(object $key, object $activateUserModel)
    {
        $this->key = $key;
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
            if ($code !== $userKey) {
                return array(
                    'content' => 'src/View/activate-user/'
                        . 'code-not-valid-info.php',
                    'activeMenu' => 'activate-user',
                    'title' => 'Informacja'
                );
            }
            if ($active) {
                return array(
                    'content' => 'src/View/activate-user/'
                        . 'account-is-active-info.php',
                    'activeMenu' => 'activate-user',
                    'title' => 'Informacja'
                );
            }
            $key = $this->key->generateKey();
            $userActive = $this->activateUserModel->setUserActive(
                (int) $id,
                $key
            );

            return array(
                'content' => 'src/View/activate-user/'
                    . 'account-activation-info.php',
                'activeMenu' => 'activate-user',
                'title' => 'Informacja',
                'userActive' => $userActive
            );
        }

        return array(
            'content' => 'src/View/activate-user/activate-user.php',
            'activeMenu' => 'activate-user',
            'title' => 'Aktywacja'
        );
    }
}
