<?php

namespace App\Kernel\Database;

class DatabaseConfiguration
{
    /**
     * @var string
     */
    protected $host;

    /**
     * @var string
     */
    protected $port;

    /**
     * @var string
     */
    protected $dbName;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string|null
     */
    protected $password;

    public function __construct(string $host, string $port, string $dbName, string $userName, string $password = null)
    {
        $this->host = $host;
        $this->port = $port;
        $this->dbName = $dbName;
        $this->username = $userName;
        $this->password = $password;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): string
    {
        return $this->port;
    }

    public function getDbName(): string
    {
        return $this->dbName;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
}
