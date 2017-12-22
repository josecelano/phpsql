<?php

namespace PhpSql;

use Doctrine\DBAL\Connection as DoctrineDbalConnection;

class DbalConnection implements Connection
{
    /** @var  DoctrineDbalConnection */
    private $connection;

    /**
     * DbalConnection constructor.
     * @param DoctrineDbalConnection $connection
     */
    public function __construct(DoctrineDbalConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return DoctrineDbalConnection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    function query($sql)
    {
        return $this->connection->query($sql);
    }

    function connect()
    {
        return $this->connection->connect();
    }

    public function getDatabase()
    {
        return $this->connection->getDatabase();
    }

    public function getUser()
    {
        return $this->connection->getUsername();
    }

    public function getPassword()
    {
        return $this->connection->getPassword();
    }

    public function getHost()
    {
        return $this->connection->getHost();
    }

    public function getPort()
    {
        return $this->connection->getPort();
    }

    public function getParams()
    {
        return $this->connection->getParams();
    }

    public function getDriver()
    {
        $params = $this->connection->getParams();
        return $params['driver'];
    }
}