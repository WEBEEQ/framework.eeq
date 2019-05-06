<?php declare(strict_types=1);

// src/Service/AdminAccountService.php
namespace App\Service;

class AdminAccountService
{
    protected $config;
    protected $html;
    protected $adminAccountModel;

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
        int $account,
        int $level,
        int $id
    ): array {
        if ($account && $account != $id) {
            header('Location: ' . $this->config->getUrl() . '/logowanie');
            exit;
        }

        $userData = $this->adminAccountModel->getUserData($id);
        if (!$userData) {
            $this->adminAccountModel->dbClose();
            header('Location: ' . $this->config->getUrl() . '/logowanie');
            exit;
        }

        $siteList = $this->adminAccountModel->getSiteList(
            $level,
            $listLimit = 10
        );
        $pageNavigator = $this->adminAccountModel->pageNavigator(
            $this->config,
            $this->html,
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
