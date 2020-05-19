<?php

declare(strict_types=1);

namespace App\Service;

class UserAccountService
{
    protected object $config;
    protected object $html;
    protected object $csrfToken;
    protected object $userAccountModel;
    protected object $userAccountValidator;

    public function __construct(
        object $config,
        object $html,
        object $csrfToken,
        object $userAccountModel,
        object $userAccountValidator
    ) {
        $this->config = $config;
        $this->html = $html;
        $this->csrfToken = $csrfToken;
        $this->userAccountModel = $userAccountModel;
        $this->userAccountValidator = $userAccountValidator;
    }

    public function variableAction(
        array $userData,
        string $name,
        string $www,
        bool $submit,
        string $token,
        int $level,
        int $id
    ): array {
        if ($submit) {
            $this->userAccountValidator->validate($name, $www, $token);
            if ($this->userAccountValidator->isValid()) {
                $siteData = $this->userAccountModel->addSiteData(
                    $id,
                    $name,
                    $www,
                    $this->config->getRemoteAddress(),
                    $this->config->getDateTimeNow()
                );
                if ($siteData) {
                    return array(
                        'content' => 'src/View/user-account/'
                            . 'site-added-info.php',
                        'activeMenu' => 'user-account',
                        'title' => 'Informacja'
                    );
                } else {
                    return array(
                        'content' => 'src/View/user-account/'
                            . 'site-not-added-info.php',
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
        $siteCount = $this->userAccountModel->getSiteCount($id);
        $pageNavigator = $this->html->preparePageNavigator(
            $this->config->getUrl() . '/konto,' . $id . ',strona,',
            $level,
            $listLimit,
            $siteCount,
            3
        );

        return array(
            'content' => 'src/View/user-account/user-account.php',
            'activeMenu' => 'user-account',
            'title' => 'Konto',
            'error' => $this->html->prepareError(
                $this->userAccountValidator->getError()
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
