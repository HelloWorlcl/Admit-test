<?php

namespace App\Kernel\Database\Connection;

use App\Kernel\Database\DatabaseConfiguration;

class MySQLConnection implements Connection
{
    /**
     * @var DatabaseConfiguration
     */
    private $configuration;

    public function __construct(DatabaseConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function getConnection(): \PDO
    {
        return new \PDO($this->getDSN(), $this->configuration->getUsername(), $this->configuration->getPassword());
    }

    protected function getDSN(): string
    {
        return sprintf(
            'mysql:host=%s;port=%s;dbname=%s',
            $this->configuration->getHost(),
            $this->configuration->getPort(),
            $this->configuration->getDbName()
        );
    }
}
