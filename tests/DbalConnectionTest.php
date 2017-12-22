<?php

namespace Test\PhpSql;

use PhpSql\DbalConnection;

class DbalConnectionTest extends CommandTestCase
{
    /** @test */
    public function it_should_be_a_dbal_connection_wrapper()
    {
        $connection = $this->createMock(\Doctrine\DBAL\Connection::class);
        $dbalConnection = new DbalConnection($connection);

        $connection->expects($this->once())->method('query');
        $dbalConnection->query('');

        $connection->expects($this->once())->method('connect');
        $dbalConnection->connect();

        $connection->expects($this->once())->method('getDatabase');
        $dbalConnection->getDatabase();

        $connection->expects($this->once())->method('getHost');
        $dbalConnection->getHost();

        $connection->expects($this->once())->method('getPort');
        $dbalConnection->getPort();

        $connection->expects($this->once())->method('getParams');
        $dbalConnection->getParams();
    }

    /** @test */
    public function it_should_return_original_dbal_connection()
    {
        $connection = $this->createMock(\Doctrine\DBAL\Connection::class);
        $dbalConnection = new DbalConnection($connection);
        $this->assertSame($connection, $dbalConnection->getConnection());
    }
}
