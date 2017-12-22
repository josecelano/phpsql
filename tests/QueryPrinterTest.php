<?php

namespace Test\PhpSql;

use PhpSql\Connection;
use PhpSql\QueryPrinter;
use Symfony\Component\Console\Output\OutputInterface;

class QueryPrinterTest extends CommandTestCase
{
    /** @test */
    public function it_should_print_anything_if_query_is_empty_or_null()
    {
        $connection = $this->createMock(Connection::class);
        $output = $this->createMock(OutputInterface::class);

        $queryPrinter = new QueryPrinter($connection, $output);

        $output->expects($this->never())->method('write');
        $output->expects($this->never())->method('writeln');

        $queryPrinter->printToConsole('');
        $queryPrinter->printToConsole(null);
    }

    /** @test */
    public function it_should_print_the_sql_exception_message_when_sql_query_fails()
    {
        $connection = $this->createMock(Connection::class);
        $output = $this->createMock(OutputInterface::class);

        $queryPrinter = new QueryPrinter($connection, $output);

        $connection
            ->expects($this->once())
            ->method('query')
            ->with('invalid sql')
            ->will($this->throwException(new \Exception('exception message')));

        $output->expects($this->once())->method('writeln')->with('<error>exception message</error>');

        $queryPrinter->printToConsole('invalid sql');
    }
}
