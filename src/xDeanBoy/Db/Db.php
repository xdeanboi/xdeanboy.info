<?php

namespace xDeanBoy\Db;

use xDeanBoy\Exceptions\DbException;

class Db
{
    private $pdo;
    private static $instance;

    /**
     * @throws DbException
     */
    public function __construct()
    {
        try {
            $options = require_once __DIR__ . '/settings.php';

            $this->pdo = new \PDO('mysql:host=' . $options['host'] . ';dbname=' . $options['dbname'],
                $options['user'],
                $options['password']);

            $this->pdo->exec('SET NAMES UTF8');
        } catch (\PDOException $e) {
            throw new DbException('Error with Db: ' . $e->getMessage());
        }
    }

    /**
     * @return self
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param string $sql
     * @param array $params
     * @param string $className
     * @return array|null
     */
    public function query(string $sql, array $params, string $className): ?array
    {
        //preparation request
        $sth = $this->pdo->prepare($sql);
        //launch request
        $result = $sth->execute($params);

        if ($result === false) {
            return null;
        }

        //return object of class
        return $sth->fetchAll(\PDO::FETCH_CLASS, $className);
    }

    /**
     * @return int
     */
    public function getLastInsertId(): int
    {
        return $this->pdo->lastInsertId();
    }
}