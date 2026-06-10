<?php
class Database
{
    private static ?PDO $connection = null;

    public static function connectDB(): PDO
    {
        if (self::$connection === null) {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            self::$connection = new PDO(
                $dsn,
                DB_USER,
                DB_PASS
            );
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Exceptions bei Datenbankfehlern
         	self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);// Fetch als assoziatives Array

        }
        return self::$connection;
    }
}

