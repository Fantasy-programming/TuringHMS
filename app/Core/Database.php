<?php

namespace Core;

// Access the pdo class
use PDO;
use PDOException;
use Exception;

class Database
{
    public $connection;
    public $statement;

    public function __construct($config, string $user = 'root', string $password = '')
    {
        try {
            $dsn = "mysql:host=" . $config['host'] . ";port=" . $config['port'] . ";dbname=" . $config['dbname'];
            $this->connection = new PDO($dsn, $user, $password, [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }
    public function query($query, array $params = [])
    {
        try {

            $this->statement = $this->connection->prepare($query);
            $this->statement->execute($params);
            return $this;
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function insertAndGetId(string $query, array $params = [])
    {
        try {

            $this->statement = $this->connection->prepare($query);
            $this->statement->execute($params);

            // After executing the INSERT query, get the last inserted ID

            $lastInsertId = $this->connection->lastInsertId();
            return $lastInsertId;
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function findorabort()
    {
        $result = $this->find();
        if (!$result) {
            abort();
        }
        return $result;
    }

    public function findorfail()
    {
        $result = $this->find();
        return $result ? $result : 0;
    }


    public function findAll()
    {
        return $this->statement->fetchAll();
    }

    public function find()
    {
        return $this->statement->fetch();
    }
}
