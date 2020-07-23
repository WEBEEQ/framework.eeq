<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\SiteRepository;

class AdminAccountService
{
    protected object $adminAccountController;
    protected object $config;
    protected object $html;

    public function __construct(
        object $adminAccountController,
        object $config,
        object $html
    ) {
        $this->adminAccountController = $adminAccountController;
        $this->config = $config;
        $this->html = $html;
    }

    public function variableAction(
        int $level,
        int $id
    ): array {
        $rm = $this->adminAccountController->getManager();

        $adminSiteList = $rm->getRepository(SiteRepository::class)
            ->getAdminSiteList(
                $level,
                $listLimit = 10
            );
        $adminSiteCount = $rm->getRepository(SiteRepository::class)
            ->getAdminSiteCount();
        $pageNavigator = $this->html->preparePageNavigator(
            $this->config->getUrl() . '/admin,' . $id . ',strona,',
            $level,
            $listLimit,
            $adminSiteCount,
            3
        );

        return array(
            'content' => 'admin-account/admin-account.php',
            'activeMenu' => 'admin-account',
            'title' => 'Admin',
            'adminSiteList' => $adminSiteList,
            'pageNavigator' => $pageNavigator
        );
    }
}
