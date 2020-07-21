<?php

declare(strict_types=1);

namespace App\Service\Api;

use App\Repository\{SiteRepository, UserRepository};

class AddSiteService
{
    protected object $addSiteController;
    protected object $config;
    protected object $addSiteValidator;

    public function __construct(
        object $addSiteController,
        object $config,
        object $addSiteValidator
    ) {
        $this->addSiteController = $addSiteController;
        $this->config = $config;
        $this->addSiteValidator = $addSiteValidator;
    }

    public function addSiteMessage(
        string $user,
        string $password,
        string $name,
        string $www
    ): object {
        $rm = $this->addSiteController->getManager();

        $this->addSiteValidator->validate(
            $user,
            $password,
            $name,
            $www
        );
        if ($this->addSiteValidator->isValid()) {
            $apiUserData = $rm->getRepository(UserRepository::class)
                ->getApiUserData($user);
            $apiSiteData = $rm->getRepository(SiteRepository::class)
                ->addApiSiteData(
                    $apiUserData['user_id'],
                    $name,
                    $www,
                    $this->config->getRemoteAddress(),
                    $this->config->getDateTimeNow()
                );
            if ($apiSiteData) {
                $this->addSiteValidator->addMessage(
                    'Strona www została dodana i oczekuje na akceptację.'
                );
                $this->addSiteValidator->setOk(true);
            } else {
                $this->addSiteValidator->addMessage(
                    'Dodanie strony www nie powiodło się.'
                );
            }
        }

        return $this->addSiteValidator;
    }
}
