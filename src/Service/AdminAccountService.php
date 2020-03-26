<?php

declare(strict_types=1);

// src/Service/AdminAccountService.php
namespace App\Service;

class AdminAccountService
{
    protected object $adminAccountModel;

    public function __construct(object $adminAccountModel)
    {
        $this->adminAccountModel = $adminAccountModel;
    }

    public function variableAction(
        int $level,
        int $id
    ): array {
        $siteList = $this->adminAccountModel->getSiteList(
            $level,
            $listLimit = 10
        );
        $pageNavigator = $this->adminAccountModel->pageNavigator(
            $id,
            $level,
            $listLimit
        );

        return array(
            'layout' => 'src/Layout/main/main.php',
            'content' => 'src/View/admin-account/admin-account.php',
            'activeMenu' => 'admin-account',
            'title' => 'Admin',
            'siteList' => $siteList,
            'pageNavigator' => $pageNavigator
        );
    }
}
