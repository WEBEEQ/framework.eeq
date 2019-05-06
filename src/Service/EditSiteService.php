<?php declare(strict_types=1);

// src/Service/EditSiteService.php
namespace App\Service;

class EditSiteService
{
    protected $config;
    protected $html;
    protected $csrfToken;
    protected $editSiteError;
    protected $editSiteModel;

    public function __construct(
        object $config,
        object $html,
        object $csrfToken,
        object $editSiteError,
        object $editSiteModel
    ) {
        $this->config = $config;
        $this->html = $html;
        $this->csrfToken = $csrfToken;
        $this->editSiteError = $editSiteError;
        $this->editSiteModel = $editSiteModel;
    }

    public function variableAction(
        string $name,
        string $www,
        int $visible,
        bool $delete,
        bool $submit,
        string $token,
        int $site,
        int $id
    ): array {
        if (!$this->editSiteModel->isUserSiteId($id, $site)) {
            $this->editSiteModel->dbClose();
            header('Location: ' . $this->config->getUrl() . '/logowanie');
            exit;
        }

        if ($submit) {
            if ($delete) {
                $siteData = $this->editSiteModel->deleteSiteData($site);

                return array(
                    'layout' => 'src/Layout/main/main.php',
                    'content' => 'src/View/edit-site/data-deletion-info.php',
                    'activeMenu' => 'edit-site',
                    'title' => 'Informacja',
                    'siteData' => $siteData
                );
            } else {
                $this->editSiteError->validate($name, $token);
                if ($this->editSiteError->isValid()) {
                    $siteData = $this->editSiteModel->setSiteData(
                        $site,
                        $visible,
                        $name,
                        $this->config->getRemoteAddress(),
                        $this->config->getDateTimeNow()
                    );

                    return array(
                        'layout' => 'src/Layout/main/main.php',
                        'content' => 'src/View/edit-site/data-record-info.php',
                        'activeMenu' => 'edit-site',
                        'title' => 'Informacja',
                        'siteData' => $siteData
                    );
                }
            }
        } else {
            $this->editSiteModel->getSiteData($site, $visible, $name, $www);
        }

        return array(
            'layout' => 'src/Layout/main/main.php',
            'content' => 'src/View/edit-site/edit-site.php',
            'activeMenu' => 'edit-site',
            'title' => 'Edycja strony',
            'error' => $this->html->prepareError(
                $this->editSiteError->getError()
            ),
            'name' => $name,
            'www' => $www,
            'visible' => $visible,
            'delete' => $delete,
            'token' => $this->csrfToken->generateToken()
        );
    }
}
