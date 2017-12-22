<?php

namespace Test\PhpSql;

use PhpSql\ConnectionFactory;

class ConnectionFactoryTest extends CommandTestCase
{
    /** @test */
    public function it_should_create_a_connection_from_parameters()
    {
        $connection = (new ConnectionFactory())->getInstance([
            'database' => 'database',
            'user' => 'user',
            'password' => 'password',
            'host' => 'host',
            'port' => 'port',
            'driver' => 'pdo_mysql',
        ]);

        $this->assertEquals('database', $connection->getDatabase());
        $this->assertEquals('user', $connection->getUser());
        $this->assertEquals('password', $connection->getPassword());
        $this->assertEquals('host', $connection->getHost());
        $this->assertEquals('port', $connection->getPort());
        $this->assertEquals('pdo_mysql', $connection->getDriver());
    }
}
