<?php
declare(strict_types = 1);

// src/Core/DataBase.php
namespace AppBundle\Core;

use AppBundle\Core\Config;

class DataBase
{
    protected $mysqlHost;
    protected $mysqlUser;
    protected $mysqlPassword;
    protected $mysqlLink;
    protected $mysqlDatabase;
    protected $mysqlTimeZone;
    protected $mysqlNames;

    public function __construct()
    {
        $this->mysqlHost = Config::getMysqlHost();
        $this->mysqlUser = Config::getMysqlUser();
        $this->mysqlPassword = Config::getMysqlPassword();
        $this->mysqlLink = null;
        $this->mysqlDatabase = Config::getMysqlDatabase();
        $this->mysqlTimeZone = Config::getMysqlTimeZone();
        $this->mysqlNames = Config::getMysqlNames();
    }

    public function dbConnect(): void
    {
        $this->mysqlLink = @mysqli_connect($this->mysqlHost, $this->mysqlUser, $this->mysqlPassword)
            or $this->printError('Could not connect to MySQL');
        mysqli_select_db($this->mysqlLink, $this->mysqlDatabase)
            or $this->printError('Could not choose the database');
        $this->dbQuery("SET `time_zone` = '" . $this->mysqlTimeZone . "'");
        $this->dbQuery("SET NAMES '" . $this->mysqlNames . "'");
    }

    public function dbClose(): void
    {
        mysqli_close($this->mysqlLink)
            or $this->printError('Could not close the connection to MySQL');
    }

    public function dbQuery(string $query)
    {
        return mysqli_query($this->mysqlLink, $query);
    }

    public function dbFetchArray($result): ?array
    {
        return mysqli_fetch_assoc($result);
    }

    public function dbNumberRows($result): int
    {
        return mysqli_num_rows($result);
    }

    public function dbAffectedRows(): int
    {
        return mysqli_affected_rows($this->mysqlLink);
    }

    public function dbInsertId(): int
    {
        return mysqli_insert_id($this->mysqlLink);
    }

    public function dbStartTransaction(): bool
    {
        return $this->dbQuery('START TRANSACTION');
    }

    public function dbCommit(): bool
    {
        return $this->dbQuery('COMMIT');
    }

    public function dbRollback(): bool
    {
        return $this->dbQuery('ROLLBACK');
    }

    private function printError(string $message): void
    {
        echo 'Error: ' . $message;
        exit;
    }
}
