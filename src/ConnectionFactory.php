<?php

namespace PhpSql;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

class ConnectionFactory
{
    /** @var Connection */
    private $connection = null;

    /**
     * @param array $options
     * @return Connection
     */
    public function getInstance(array $options)
    {
        if (!$this->connection) {
            $this->connection = new DbalConnection(
                DriverManager::getConnection([
                    'dbname' => $options['database'],
                    'user' => $options['user'],
                    'password' => $options['password'],
                    'host' => $options['host'],
                    'port' => $options['port'],
                    'driver' => $options['driver'],
                ], new Configuration()));
        }

        return $this->connection;
    }
}
