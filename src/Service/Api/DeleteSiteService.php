<?php

declare(strict_types=1);

namespace App\Service\Api;

use App\Repository\SiteRepository;

class DeleteSiteService
{
    protected object $controller;
    protected object $deleteSiteValidator;

    public function __construct(
        object $controller,
        object $deleteSiteValidator
    ) {
        $this->controller = $controller;
        $this->deleteSiteValidator = $deleteSiteValidator;
    }

    public function deleteSiteMessage(
        string $user,
        string $password,
        int $site
    ): object {
        $rm = $this->controller->getManager();

        $this->deleteSiteValidator->validate(
            $user,
            $password,
            $site
        );
        if ($this->deleteSiteValidator->isValid()) {
            $apiSiteData = $rm->getRepository(SiteRepository::class)
                ->deleteApiSiteData($site);
            if ($apiSiteData) {
                $this->deleteSiteValidator->addMessage(
                    'Dane strony www zostały usunięte.'
                );
                $this->deleteSiteValidator->setOk(true);
            } else {
                $this->deleteSiteValidator->addMessage(
                    'Usunięcie danych strony www nie powiodło się.'
                );
            }
        }

        return $this->deleteSiteValidator;
    }
}
