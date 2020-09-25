<?php

declare(strict_types=1);

namespace Enterprise;

class DB
{
    /** @var \PDO $pdo PHP data object handle to database */
    protected static ?\PDO $pdo = null;

    /**
     * The \Enterprise\DB constructor is declared private so
     * that the class cannot be instantiated.
     *
     * @return void
     */
    private function __constructor() { }

    public static function getHandle() : \PDO
    {
        if (self::$pdo === null) {
            try {
                self::$pdo = new \PDO('pgsql:host=localhost;dbname=enterprisedevdb', 'enterprisedb', 'enterprisedb');
            } catch (\PDOException $pdoException) {
                die($pdoException->getMessage());
            }
        }
        
        return self::$pdo;
    }
}
