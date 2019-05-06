<?php declare(strict_types=1);

// src/Core/Config.php
namespace App\Core;

class Config
{
    protected static $url;
    protected static $serverName;
    protected static $serverDomain;
    protected static $serverEmail;
    protected static $administratorEmail;
    protected static $mysqlHost;
    protected static $mysqlPort;
    protected static $mysqlUser;
    protected static $mysqlPassword;
    protected static $mysqlDatabase;
    protected static $mysqlTimeZone;
    protected static $mysqlNames;

    public static function init(): void
    {
        $database = require(
            $_SERVER['DOCUMENT_ROOT'] . '/src/Config/database.php'
        );

        self::$url = 'http' . (($_SERVER['SERVER_PORT'] == 443) ? 's' : '')
            . '://' . $_SERVER['HTTP_HOST'];
        self::$serverName = $_SERVER['SERVER_NAME'];
        self::$serverDomain = str_replace('www.', '', self::$serverName);
        self::$serverEmail = 'kontakt@' . self::$serverDomain;
        self::$administratorEmail = $database['admin_email'];
        self::$mysqlHost = $database['db_host'];
        self::$mysqlPort = $database['db_port'];
        self::$mysqlUser = $database['db_user'];
        self::$mysqlPassword = $database['db_password'];
        self::$mysqlDatabase = $database['db_database'];
        self::$mysqlTimeZone = $database['db_time_zone'];
        self::$mysqlNames = $database['db_names'];
    }

    public static function getUrl(): string
    {
        return self::$url;
    }

    public static function getServerName(): string
    {
        return self::$serverName;
    }

    public static function getServerDomain(): string
    {
        return self::$serverDomain;
    }

    public static function getServerEmail(): string
    {
        return self::$serverEmail;
    }

    public static function getAdministratorEmail(): string
    {
        return self::$administratorEmail;
    }

    public static function getMysqlHost(): string
    {
        return self::$mysqlHost;
    }

    public static function getMysqlPort(): string
    {
        return self::$mysqlPort;
    }

    public static function getMysqlUser(): string
    {
        return self::$mysqlUser;
    }

    public static function getMysqlPassword(): string
    {
        return self::$mysqlPassword;
    }

    public static function getMysqlDatabase(): string
    {
        return self::$mysqlDatabase;
    }

    public static function getMysqlTimeZone(): string
    {
        return self::$mysqlTimeZone;
    }

    public static function getMysqlNames(): string
    {
        return self::$mysqlNames;
    }
}
