<?php declare(strict_types=1);

namespace Library\Sieciq;

class Config
{
    protected static $url = 'http://127.0.0.12';
    protected static $addSitePath = '/rest/dodawanie';
    protected static $updateSitePath = '/rest/aktualizacja';
    protected static $deleteSitePath = '/rest/usuwanie';

    public static function getAddSitePathUrl(): string
    {
        return self::$url . self::$addSitePath;
    }

    public static function getUpdateSitePathUrl(): string
    {
        return self::$url . self::$updateSitePath;
    }

    public static function getDeleteSitePathUrl(): string
    {
        return self::$url . self::$deleteSitePath;
    }
}
