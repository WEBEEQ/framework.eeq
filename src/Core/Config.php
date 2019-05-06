<?php
declare(strict_types = 1);

// src/Core/Config.php
namespace AppBundle\Core;

class Config
{
    protected static $mysqlHost = 'localhost';
    protected static $mysqlUser = 'root';
    protected static $mysqlPassword = 'root';
    protected static $mysqlDatabase = 'webeeq_toposoba';
    protected static $mysqlTimeZone = '+1:00';
    protected static $mysqlNames = 'utf8';
    protected static $administratorEmail = 'kontakt@toposoba.pl';

    public static function getMysqlHost(): string
    {
        return self::$mysqlHost;
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

    public static function getServerName(): string
    {
        return $_SERVER['SERVER_NAME'];
    }

    public static function getServerDomain(): string
    {
        return str_replace('www.', '', self::getServerName());
    }

    public static function getServerEmail(): string
    {
        return 'kontakt@' . self::getServerDomain();
    }

    public static function getAdministratorEmail(): string
    {
        return self::$administratorEmail;
    }
}
