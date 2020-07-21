<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\SiteRepository;

class EditSiteService
{
    protected object $editSiteController;
    protected object $config;
    protected object $html;
    protected object $csrfToken;
    protected object $editSiteValidator;

    public function __construct(
        object $editSiteController,
        object $config,
        object $html,
        object $csrfToken,
        object $editSiteValidator
    ) {
        $this->editSiteController = $editSiteController;
        $this->config = $config;
        $this->html = $html;
        $this->csrfToken = $csrfToken;
        $this->editSiteValidator = $editSiteValidator;
    }

    public function variableAction(
        string $name,
        string $www,
        int $visible,
        bool $delete,
        bool $submit,
        string $token,
        int $site
    ): array {
        $rm = $this->editSiteController->getManager();

        if ($submit) {
            if ($delete) {
                $editingSiteData = $rm->getRepository(SiteRepository::class)
                    ->deleteEditingSiteData($site);

                return array(
                    'content' => 'src/View/edit-site/data-deletion-info.php',
                    'activeMenu' => 'edit-site',
                    'title' => 'Informacja',
                    'editingSiteData' => $editingSiteData
                );
            }
            $this->editSiteValidator->validate($name, $token);
            if ($this->editSiteValidator->isValid()) {
                $editingSiteData = $rm->getRepository(SiteRepository::class)
                    ->setEditingSiteData(
                        $site,
                        $visible,
                        $name,
                        $this->config->getRemoteAddress(),
                        $this->config->getDateTimeNow()
                    );

                return array(
                    'content' => 'src/View/edit-site/data-record-info.php',
                    'activeMenu' => 'edit-site',
                    'title' => 'Informacja',
                    'editingSiteData' => $editingSiteData
                );
            }
        } else {
            $editingSiteData = $rm->getRepository(SiteRepository::class)
                ->getEditingSiteData($site);
        }

        return array(
            'content' => 'src/View/edit-site/edit-site.php',
            'activeMenu' => 'edit-site',
            'title' => 'Edycja strony',
            'error' => $this->html->prepareError(
                $this->editSiteValidator->getError()
            ),
            'name' => $editingSiteData['site_name'] ?? $name,
            'www' => $editingSiteData['site_url'] ?? $www,
            'visible' => $editingSiteData['site_visible'] ?? $visible,
            'delete' => $delete,
            'token' => $this->csrfToken->generateToken()
        );
    }
}
