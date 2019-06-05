<?php declare(strict_types=1);

// src/Service/Api/DeleteSiteService.php
namespace App\Service\Api;

class DeleteSiteService
{
    protected $deleteSiteModel;
    protected $deleteSiteValidator;

    public function __construct(
        object $deleteSiteModel,
        object $deleteSiteValidator
    ) {
        $this->deleteSiteModel = $deleteSiteModel;
        $this->deleteSiteValidator = $deleteSiteValidator;
    }

    public function deleteSiteMessage($user, $password, $site): object
    {
        $this->deleteSiteValidator->validate(
            $user,
            $password,
            $site
        );
        if ($this->deleteSiteValidator->isValid()) {
            if ($this->deleteSiteModel->deleteSiteData($site)) {
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
