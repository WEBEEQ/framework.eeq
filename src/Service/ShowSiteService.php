<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\UserRepository;

class ShowSiteService
{
    protected object $controller;
    protected object $config;

    public function __construct(
        object $controller,
        object $config
    ) {
        $this->controller = $controller;
        $this->config = $config;
    }

    public function wwwAction(int $id): array
    {
        $rm = $this->controller->getManager();
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
            'layout' => 'src/Layout/show-site/main.php',
            'content' => 'src/View/show-site/show-site.php',
            'activeMenu' => 'show-site',
            'title' => 'Pokaz stron',
            'www' => $showData['site_url'] ?? $www
        );
    }
}
