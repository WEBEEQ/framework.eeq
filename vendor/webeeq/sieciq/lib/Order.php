<?php

declare(strict_types=1);

namespace Webeeq\Sieciq;

use Webeeq\Sieciq\Http;

class Order extends Http
{
    protected object $config;

    public function __construct(object $config)
    {
        $this->config = $config;
    }

    public function addSite(array $auth, array $data): array
    {
        return $this->doPost(
            $this->config->getAddSitePathUrl(),
            $auth,
            $data
        );
    }

    public function updateSite(array $auth, array $data): array
    {
        return $this->doPut(
            $this->config->getUpdateSitePathUrl(),
            $auth,
            $data
        );
    }

    public function deleteSite(array $auth, array $data): array
    {
        return $this->doDelete(
            $this->config->getDeleteSitePathUrl(),
            $auth,
            $data
        );
    }
}
