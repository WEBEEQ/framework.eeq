<?php

declare(strict_types=1);

namespace Webeeq\Sieciq;

class Config
{
    protected string $url = 'http://127.0.0.15';
    protected string $addSitePath = '/api/dodaj-strone';
    protected string $updateSitePath = '/api/zaktualizuj-strone';
    protected string $deleteSitePath = '/api/usun-strone';

    public function getAddSitePathUrl(): string
    {
        return $this->url . $this->addSitePath;
    }

    public function getUpdateSitePathUrl(): string
    {
        return $this->url . $this->updateSitePath;
    }

    public function getDeleteSitePathUrl(): string
    {
        return $this->url . $this->deleteSitePath;
    }
}
