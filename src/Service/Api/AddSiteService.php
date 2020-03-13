<?php declare(strict_types=1);

// src/Service/Api/AddSiteService.php
namespace App\Service\Api;

class AddSiteService
{
    protected $config;
    protected $addSiteModel;
    protected $addSiteValidator;

    public function __construct(
        object $config,
        object $addSiteModel,
        object $addSiteValidator
    ) {
        $this->config = $config;
        $this->addSiteModel = $addSiteModel;
        $this->addSiteValidator = $addSiteValidator;
    }

    public function addSiteMessage(
        string $user,
        string $password,
        string $name,
        string $www
    ): object {
        $this->addSiteValidator->validate(
            $user,
            $password,
            $name,
            $www,
            $id
        );
        if ($this->addSiteValidator->isValid()) {
            $siteData = $this->addSiteModel->addSiteData(
                (int) $id,
                $name,
                $www,
                $this->config->getRemoteAddress(),
                $this->config->getDateTimeNow()
            );
            if ($siteData) {
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
