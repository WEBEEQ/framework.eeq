<?php declare(strict_types=1);

// src/Service/Api/UpdateSiteService.php
namespace App\Service\Api;

class UpdateSiteService
{
    protected $config;
    protected $updateSiteModel;
    protected $updateSiteValidator;

    public function __construct(
        object $config,
        object $updateSiteModel,
        object $updateSiteValidator
    ) {
        $this->config = $config;
        $this->updateSiteModel = $updateSiteModel;
        $this->updateSiteValidator = $updateSiteValidator;
    }

    public function updateSiteMessage(
        string $user,
        string $password,
        int $site,
        string $name,
        int $visible
    ): object {
        $this->updateSiteValidator->validate(
            $user,
            $password,
            $site,
            $name
        );
        if ($this->updateSiteValidator->isValid()) {
            $siteData = $this->updateSiteModel->setSiteData(
                $site,
                $visible,
                $name,
                $this->config->getRemoteAddress(),
                $this->config->getDateTimeNow()
            );
            if ($siteData) {
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
