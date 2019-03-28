<?php declare(strict_types=1);

namespace OpenTraining\Statie\Tests\Posts\Year2018\Cart\Utils;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\PDOMySql\Driver as MySqlDriver;
use Doctrine\DBAL\Driver\PDOPgSql\Driver as PgSqlDriver;
use Doctrine\DBAL\Driver\PDOSqlite\Driver as SqliteDriver;

final class ConnectionManager
{
    /**
     * @var Connection
     */
    private static $connectionForCreatingDatabases;

    public static function dropAndCreateDatabase(): void
    {
        if (self::$connectionForCreatingDatabases === null) {
            self::$connectionForCreatingDatabases = new Connection([
                'user' => self::getUser(),
                'password' => self::getPassword(),
                'host' => self::getHost(),
            ], self::getDriver());
        }

        self::$connectionForCreatingDatabases->exec(sprintf('DROP DATABASE IF EXISTS %s', self::getDbName()));
        self::$connectionForCreatingDatabases->exec(sprintf('CREATE DATABASE %s', self::getDbName()));
    }

    public static function createConnection(): Connection
    {
        return new Connection([
            'user' => self::getUser(),
            'password' => self::getPassword(),
            'dbname' => self::getDbName(),
            'host' => self::getHost(),
        ], self::getDriver());
    }

    public static function createSqliteMemoryConnection(): Connection
    {
        return new Connection([
            'memory' => true,
        ], new SqliteDriver());
    }

    private static function getUser(): ?string
    {
        return $GLOBALS['DB_USER'] ?? null;
    }

    private static function getPassword(): ?string
    {
        return $GLOBALS['DB_PASSWORD'] ?? null;
    }

    private static function getHost(): ?string
    {
        return $GLOBALS['DB_HOST'] ?? null;
    }

    private static function getDriver(): Driver
    {
        if ($GLOBALS['DB_DRIVER'] === 'pdo_pgsql') {
            return new PgSqlDriver();
        }

        if ($GLOBALS['DB_DRIVER'] === 'pdo_mysql') {
            return new MySqlDriver();
        }
    }

    private static function getDbName(): ?string
    {
        return $GLOBALS['DB_DBNAME'] ?? null;
    }
}
