<?php

namespace App\Repositories;

use App\Kernel\Database\Connection\Connection;
use App\Kernel\Database\Connection\MySQLConnection;
use App\Models\AbstractModel;

abstract class AbstractRepository
{
    /**
     * @var Connection
     */
    protected $connection;

    public function __construct()
    {
        $this->connection = (new MySQLConnection())->getConnection();
    }

    protected function getConnection(): \PDO
    {
        return $this->connection;
    }

    abstract public function findAll(): array;

    /**
     * @return AbstractModel
     */
    abstract public function findById(int $id);

    public function __destruct()
    {
        $this->connection = null;
    }
}
