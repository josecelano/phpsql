<?php

namespace Test\PhpSql;

use Doctrine\DBAL\Driver\Statement;
use PhpSql\Connection;
use PhpSql\ConnectionFactory;
use PhpSql\StandardInputReader;

abstract class CommandTestCase extends \PHPUnit_Framework_TestCase
{
    /** @var  \Symfony\Component\Console\Application; */
    protected $application;

    /** @var  \Symfony\Component\Console\Command\Command */
    protected $command;

    /** @var \Symfony\Component\Console\Tester\CommandTester */
    protected $commandTester;

    /**
     * @param $expected
     * @param $actual
     * @param string $message
     * @param float $delta
     * @param int $maxDepth
     * @param bool $canonicalize
     * @param bool $ignoreCase
     */
    protected function commandShouldDisplay($expected, $actual, $message = '', $delta = 0.0, $maxDepth = 10, $canonicalize = false, $ignoreCase = false)
    {
        $expected = $this->normalizeLineBreaks($expected);
        $actual = $this->normalizeLineBreaks($actual);
        $expected = $this->addLineBreakAtTheEndIfDoesNotHave($expected);

        $this->assertEquals($expected, $actual, $message, $delta, $maxDepth, $canonicalize, $ignoreCase);
    }

    /**
     * @param string $expected
     * @return string
     */
    protected function normalizeLineBreaks($expected)
    {
        return str_replace("\r", '', $expected);
    }

    /**
     * @param $expected
     * @return string
     */
    protected function addLineBreakAtTheEndIfDoesNotHave($expected)
    {
        $lastChar = substr($expected, -1);
        if ($lastChar != "\n" && $lastChar != "\r\n") {
            $expected .= "\n";
        }
        return $expected;
    }

    /**
     * @return ConnectionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockConnectionFactory()
    {
        $connectionFactory = $this->createMock(ConnectionFactory::class);

        $connectionFactory->expects($this->any())
            ->method('getInstance')
            ->will($this->returnValue($this->mockConnection()));

        return $connectionFactory;
    }

    /**
     * @param array $rows
     * @return ConnectionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockConnectionFactoryWithQueryResult($rows)
    {
        $connectionFactory = $this->createMock(ConnectionFactory::class);

        $connectionFactory->expects($this->any())
            ->method('getInstance')
            ->will($this->returnValue($this->mockConnectionReturningRows($rows)));

        return $connectionFactory;
    }

    /**
     * @param $error
     * @return ConnectionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockConnectionFactoryWithInvalidConnection($error)
    {
        $connectionFactory = $this->createMock(ConnectionFactory::class);

        $connectionFactory->expects($this->any())
            ->method('getInstance')
            ->will($this->returnValue($this->mockInvalidConnection($error)));

        return $connectionFactory;
    }

    /**
     * @param $line
     * @return StandardInputReader|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockStandardInputReader($line = '')
    {
        $standardInputReader = $this->createMock(StandardInputReader::class);

        $lines = [$line . "\n", false];

        $standardInputReader
            ->method('readLine')
            ->will($this->onConsecutiveCalls(...$lines));

        return $standardInputReader;
    }

    /**
     * @return \PhpSql\DbalConnection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockConnection()
    {
        $connection = $this->createMock(Connection::class);

        $connection
            ->expects($this->any())
            ->method('connect');

        return $connection;
    }

    /**
     * @param $rows
     * @return \PhpSql\DbalConnection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockConnectionReturningRows($rows)
    {
        $connection = $this->createMock(Connection::class);

        $connection
            ->expects($this->any())
            ->method('connect');

        $connection
            ->expects($this->any())
            ->method('query')
            ->will($this->returnValue($this->mockStatementReturningRows($rows)));

        return $connection;
    }

    /**
     * @param $error
     * @return \PhpSql\DbalConnection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockInvalidConnection($error)
    {
        $connection = $this->createMock(Connection::class);

        $connection
            ->expects($this->any())
            ->method('connect')
            ->will($this->throwException(new \Exception($error)));

        return $connection;
    }

    /**
     * @param $rows
     * @return Statement|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockStatementReturningRows($rows)
    {
        $statement = $this->createMock(Statement::class);

        $rows[] = false;

        $statement
            ->method('fetch')
            ->will($this->onConsecutiveCalls(...$rows));

        $statement
            ->method('rowCount')
            ->will($this->returnValue(count($rows) - 1));

        return $statement;
    }

    /**
     * @param $class
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockClass($class)
    {
        return $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
