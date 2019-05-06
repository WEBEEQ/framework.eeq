<?php
declare(strict_types = 1);

// src/Core/Config.php
namespace AppBundle\Core;

class Config
{
    protected static $mysqlHost;
    protected static $mysqlPort;
    protected static $mysqlUser;
    protected static $mysqlPassword;
    protected static $mysqlDatabase;
    protected static $mysqlTimeZone;
    protected static $mysqlNames;
    protected static $administratorEmail;

    public static function init(): void
    {
        $database = require($_SERVER['DOCUMENT_ROOT'] . '/src/Config/database.php');

        self::$mysqlHost = $database['db_host'];
        self::$mysqlPort = $database['db_port'];
        self::$mysqlUser = $database['db_user'];
        self::$mysqlPassword = $database['db_password'];
        self::$mysqlDatabase = $database['db_database'];
        self::$mysqlTimeZone = $database['db_time_zone'];
        self::$mysqlNames = $database['db_names'];
        self::$administratorEmail = $database['admin_email'];
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
