<?php

declare(strict_types=1);

namespace App\Service\Api;

use App\Repository\SiteRepository;

class UpdateSiteService
{
    protected object $updateSiteController;
    protected object $config;
    protected object $updateSiteValidator;

    public function __construct(
        object $updateSiteController,
        object $config,
        object $updateSiteValidator
    ) {
        $this->updateSiteController = $updateSiteController;
        $this->config = $config;
        $this->updateSiteValidator = $updateSiteValidator;
    }

    public function updateSiteMessage(
        string $user,
        string $password,
        int $site,
        string $name,
        int $visible
    ): object {
        $rm = $this->updateSiteController->getManager();

        $this->updateSiteValidator->validate(
            $user,
            $password,
            $site,
            $name
        );
        if ($this->updateSiteValidator->isValid()) {
            $apiSiteData = $rm->getRepository(SiteRepository::class)
                ->setApiSiteData(
                    $site,
                    $visible,
                    $name,
                    $this->config->getRemoteAddress(),
                    $this->config->getDateTimeNow()
                );
            if ($apiSiteData) {
                $this->updateSiteValidator->addMessage(
                    'Dane strony www zostały zapisane.'
                );
                $this->updateSiteValidator->setOk(true);
            } else {
                $this->updateSiteValidator->addMessage(
                    'Zapisanie danych strony www nie powiodło się.'
                );
            }
        }

        return $this->updateSiteValidator;
    }
}
