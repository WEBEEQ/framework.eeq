<?php

declare(strict_types=1);

// src/Service/ShowSiteService.php
namespace App\Service;

class ShowSiteService
{
    protected $config;
    protected $showSiteModel;

    public function __construct(
        object $config,
        object $showSiteModel
    ) {
        $this->config = $config;
        $this->showSiteModel = $showSiteModel;
    }

    public function wwwAction(int $id): array
    {
        $www = '';

        if (!$this->showSiteModel->isUserMaxShow($id)) {
            $www = $this->showSiteModel->getSiteRandomUrl(
                $id,
                $user,
                $show
            );
            if ($www) {
                $this->showSiteModel->setUserShow(
                    $id,
                    (int) $user,
                    (int) $show
                );
            } else {
                $www = $this->showSiteModel->getSiteRandomUrl(
                    $id,
                    $user,
                    $show,
                    0
                );
                if ($www) {
                    $this->showSiteModel->setUserShow(
                        $id,
                        (int) $user,
                        (int) $show,
                        0
                    );
                }
            }
        } else {
            $www = $this->config->getUrl() . '/info';
        }

        return array(
            'layout' => 'src/Layout/show-site/main.php',
            'content' => 'src/View/show-site/show-site.php',
            'activeMenu' => 'show-site',
            'title' => 'Pokaz stron',
            'www' => $www
        );
    }
}
