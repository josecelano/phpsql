<?php

namespace Test\PhpSql;

use PhpSql\Connection;
use PhpSql\ConnectionTester;

class ConnectionTesterTest extends CommandTestCase
{
    /** @test */
    public function it_should_test_if_connection_can_connect_to_database()
    {
        $connectionTester = new ConnectionTester();
        $this->assertTrue($connectionTester->test($this->createMock(Connection::class)));
    }

    /**
     * @test
     * @expectedException
     */
    public function it_should_return_exception_message_if_connection_fails()
    {
        $connection = $this->createMock(Connection::class);

        $connection->expects($this->once())
            ->method('connect')
            ->will($this->throwException(new \Exception));

        (new ConnectionTester())->test($connection);
    }
}
