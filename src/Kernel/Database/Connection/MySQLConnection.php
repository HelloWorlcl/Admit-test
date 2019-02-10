<?php

namespace App\Kernel\Database\Connection;

use Symfony\Component\Dotenv\Dotenv;

class MySQLConnection implements Connection
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

    public function __construct()
    {
        //TODO: move this using to more appropriate place
        $dotenv = new Dotenv();
        $dotenv->load('./../.env');

        $this->host = getenv('DB_HOST');
        $this->post = getenv('DB_PORT');
        $this->dbName = getenv('DB_NAME');
        $this->username = getenv('DB_USERNAME');
        $this->password = getenv('DB_PASSWORD');
    }

    public function getConnection(): \PDO
    {
        return new \PDO($this->getDSN(), $this->username, $this->password);
    }

    protected function getDSN(): string
    {
        return 'mysql:host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->dbName;
    }
}
