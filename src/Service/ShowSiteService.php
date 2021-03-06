<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\UserRepository;

class ShowSiteService
{
    protected object $showSiteController;
    protected object $config;

    public function __construct(
        object $showSiteController,
        object $config
    ) {
        $this->showSiteController = $showSiteController;
        $this->config = $config;
    }

    public function wwwAction(int $id): array
    {
        $rm = $this->showSiteController->getManager();
        $www = '';

        if (!$rm->getRepository(UserRepository::class)->isUserMaxShow($id)) {
            $showData = $rm->getRepository(UserRepository::class)
                ->getShowData($id);
            if ($showData['site_url']) {
                $rm->getRepository(UserRepository::class)->setUserShow(
                    $id,
                    $showData['user_id'],
                    $showData['user_show']
                );
            } else {
                $showData = $rm->getRepository(UserRepository::class)
                    ->getShowData($id, 0);
                if ($showData['site_url']) {
                    $rm->getRepository(UserRepository::class)->setUserShow(
                        $id,
                        $showData['user_id'],
                        $showData['user_show'],
                        0
                    );
                }
            }
        } else {
            $www = $this->config->getUrl() . '/info';
        }

        return array(
            'layout' => 'layout/show-site/main.php',
            'content' => 'show-site/show-site.php',
            'activeMenu' => 'show-site',
            'title' => 'Pokaz stron',
            'www' => $showData['site_url'] ?? $www
        );
    }
}
