<?php

namespace App\Kernel\Database\Connection;

interface Connection
{
    public function getConnection(): \PDO;
}
