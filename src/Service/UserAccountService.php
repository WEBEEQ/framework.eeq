<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\{SiteRepository, UserRepository};

class UserAccountService
{
    protected object $userAccountController;
    protected object $config;
    protected object $html;
    protected object $csrfToken;
    protected object $userAccountValidator;

    public function __construct(
        object $userAccountController,
        object $config,
        object $html,
        object $csrfToken,
        object $userAccountValidator
    ) {
        $this->userAccountController = $userAccountController;
        $this->config = $config;
        $this->html = $html;
        $this->csrfToken = $csrfToken;
        $this->userAccountValidator = $userAccountValidator;
    }

    public function variableAction(
        string $name,
        string $www,
        bool $submit,
        string $token,
        int $level,
        int $id
    ): array {
        $rm = $this->userAccountController->getManager();

        if ($submit) {
            $this->userAccountValidator->validate($name, $www, $token);
            if ($this->userAccountValidator->isValid()) {
                $accountSiteData = $rm->getRepository(SiteRepository::class)
                    ->addAccountSiteData(
                        $id,
                        $name,
                        $www,
                        $this->config->getRemoteAddress(),
                        $this->config->getDateTimeNow()
                    );
                if ($accountSiteData) {
                    return array(
                        'content' => 'user-account/site-added-info.php',
                        'activeMenu' => 'user-account',
                        'title' => 'Informacja'
                    );
                } else {
                    return array(
                        'content' => 'user-account/site-not-added-info.php',
                        'activeMenu' => 'user-account',
                        'title' => 'Informacja'
                    );
                }
            }
        }

        $accountUserData = $rm->getRepository(UserRepository::class)
            ->getAccountUserData($id);
        $accountSiteList = $rm->getRepository(SiteRepository::class)
            ->getAccountSiteList(
                $id,
                $level,
                $listLimit = 10
            );
        $accountSiteCount = $rm->getRepository(SiteRepository::class)
            ->getAccountSiteCount($id);
        $pageNavigator = $this->html->preparePageNavigator(
            $this->config->getUrl() . '/konto,' . $id . ',strona,',
            $level,
            $listLimit,
            $accountSiteCount,
            3
        );

        return array(
            'content' => 'user-account/user-account.php',
            'activeMenu' => 'user-account',
            'title' => 'Konto',
            'error' => $this->html->prepareError(
                $this->userAccountValidator->getError()
            ),
            'name' => $name,
            'www' => $www,
            'token' => $this->csrfToken->generateToken(),
            'accountUserData' => $accountUserData,
            'accountSiteList' => $accountSiteList,
            'pageNavigator' => $pageNavigator
        );
    }
}
