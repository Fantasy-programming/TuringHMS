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

    public function __construct($config, $user = 'root', $password = '')
    {
        try {

            $dsn = "mysql:" . http_build_query($config, '', ';');
            $this->connection = new PDO($dsn, $user, $password, [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }
    public function query($query, $params = [])
    {
        try {

            $this->statement = $this->connection->prepare($query);
            $success = $this->statement->execute($params);
            return $success ? 1 : 0;
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

    public function findallorfail()
    {
        $result = $this->findall();
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
