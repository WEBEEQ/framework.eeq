<?php

declare(strict_types=1);

namespace App\Service;

class AdminAccountService
{
    protected object $config;
    protected object $html;
    protected object $adminAccountModel;

    public function __construct(
        object $config,
        object $html,
        object $adminAccountModel
    ) {
        $this->config = $config;
        $this->html = $html;
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
        $siteCount = $this->adminAccountModel->getSiteCount();
        $pageNavigator = $this->html->preparePageNavigator(
            $this->config->getUrl() . '/admin,' . $id . ',strona,',
            $level,
            $listLimit,
            $siteCount,
            3
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
