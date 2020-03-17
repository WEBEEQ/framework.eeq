<?php

declare(strict_types=1);

// src/Core/Config.php
namespace App\Core;

class Config
{
    protected $url;
    protected $serverName;
    protected $serverDomain;
    protected $serverEmail;
    protected $adminEmail;
    protected $remoteAddress;
    protected $dateTimeNow;

    public function __construct()
    {
        $this->url = 'http' . (($_SERVER['SERVER_PORT'] == 443) ? 's' : '')
            . '://' . $_SERVER['HTTP_HOST'];
        $this->serverName = $_SERVER['SERVER_NAME'];
        $this->serverDomain = str_replace('www.', '', $this->serverName);
        $this->serverEmail = 'kontakt@' . $this->serverDomain;
        $this->adminEmail = $this->serverEmail;
        $this->remoteAddress = $_SERVER['REMOTE_ADDR'];
        $this->dateTimeNow = date('Y-m-d H:i:s');
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getServerName(): string
    {
        return $this->serverName;
    }

    public function getServerDomain(): string
    {
        return $this->serverDomain;
    }

    public function getServerEmail(): string
    {
        return $this->serverEmail;
    }

    public function getAdminEmail(): string
    {
        return $this->adminEmail;
    }

    public function getRemoteAddress(): string
    {
        return $this->remoteAddress;
    }

    public function getDateTimeNow(): string
    {
        return $this->dateTimeNow;
    }
}
