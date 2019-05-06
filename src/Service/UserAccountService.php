<?php declare(strict_types=1);

// src/Service/UserAccountService.php
namespace App\Service;

class UserAccountService
{
    protected $config;
    protected $html;
    protected $csrfToken;
    protected $userAccountError;
    protected $userAccountModel;

    public function __construct(
        object $config,
        object $html,
        object $csrfToken,
        object $userAccountError,
        object $userAccountModel
    ) {
        $this->config = $config;
        $this->html = $html;
        $this->csrfToken = $csrfToken;
        $this->userAccountError = $userAccountError;
        $this->userAccountModel = $userAccountModel;
    }

    public function variableAction(
        string $name,
        string $www,
        bool $submit,
        string $token,
        int $account,
        int $level,
        int $id
    ): array {
        if ($account && $account != $id) {
            header('Location: ' . $this->config->getUrl() . '/logowanie');
            exit;
        }

        $userData = $this->userAccountModel->getUserData($id);
        if (!$userData) {
            $this->userAccountModel->dbClose();
            header('Location: ' . $this->config->getUrl() . '/logowanie');
            exit;
        }

        if ($submit) {
            $this->userAccountError->validate($name, $www, $token);
            if ($this->userAccountError->isValid()) {
                $siteData = $this->userAccountModel->addSiteData(
                    $id,
                    $name,
                    $www,
                    $this->config->getRemoteAddress(),
                    $this->config->getDateTimeNow()
                );
                if ($siteData) {
                    return array(
                        'layout' => 'src/Layout/main/main.php',
                        'content' =>
                            'src/View/user-account/site-added-info.php',
                        'activeMenu' => 'user-account',
                        'title' => 'Informacja'
                    );
                } else {
                    return array(
                        'layout' => 'src/Layout/main/main.php',
                        'content' =>
                            'src/View/user-account/site-not-added-info.php',
                        'activeMenu' => 'user-account',
                        'title' => 'Informacja'
                    );
                }
            }
        }

        $siteList = $this->userAccountModel->getSiteList(
            $id,
            $level,
            $listLimit = 10
        );
        $pageNavigator = $this->userAccountModel->pageNavigator(
            $this->config,
            $this->html,
            $id,
            $level,
            $listLimit
        );

        return array(
            'layout' => 'src/Layout/main/main.php',
            'content' => 'src/View/user-account/user-account.php',
            'activeMenu' => 'user-account',
            'title' => 'Konto',
            'error' => $this->html->prepareError(
                $this->userAccountError->getError()
            ),
            'name' => $name,
            'www' => $www,
            'token' => $this->csrfToken->generateToken(),
            'userData' => $userData,
            'siteList' => $siteList,
            'pageNavigator' => $pageNavigator
        );
    }
}
