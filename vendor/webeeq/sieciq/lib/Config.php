<?php declare(strict_types=1);

namespace Webeeq\Sieciq;

class Config
{
    protected $url = 'http://127.0.0.15';
    protected $addSitePath = '/api/dodawanie';
    protected $updateSitePath = '/api/aktualizacja';
    protected $deleteSitePath = '/api/usuwanie';

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
