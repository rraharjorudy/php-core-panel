<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function connect(): PDO
    {
        if (self::$instance === null) {
            $config = include base_path('config/database.php');

            try {
                $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";
                self::$instance = new PDO($dsn, $config['username'], $config['password']);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die('Database Connection Failed: ' . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
