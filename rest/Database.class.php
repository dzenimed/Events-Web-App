<?php

require_once __DIR__."/config.php";

class Database
{
    private static $conn;

    private function __construct()
    {
    }

    /**
     * @return Database
     */
    public static function getInstance()
    {
        if (is_null(static::$conn)) {
            try {
                static::$conn = new PDO("mysql:host=".Config::DB_HOST.";dbname=".Config::DB_SCHEME, Config::DB_USERNAME, Config::DB_PASSWORD);
                static::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                throw $e;
            }
        }

        return static::$conn;
    }

    /**
     * Disable the cloning of this class.
     *
     * @return void
     */
    final public function __clone()
    {
        throw new Exception('Feature disabled.');
    }

    /**
     * Disable the wakeup of this class.
     *
     * @return void
     */
    final public function __wakeup()
    {
        throw new Exception('Feature disabled.');
    }
}
