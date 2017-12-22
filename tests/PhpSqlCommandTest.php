<?php

namespace Test\PhpSql;

use PhpSql\PhpSqlCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class PhpSqlCommandTest extends CommandTestCase
{
    /** @var PhpSqlCommand */
    protected $command;

    public function setUp()
    {
        $this->application = new Application();
    }

    /** @test */
    public function it_should_execute_a_sql_query_passed_as_console_argument()
    {
        $rows = [
            [
                "column1" => "value 1",
                "column2" => "value 2",
            ],
        ];

        $connectionFactory = $this->mockConnectionFactoryWithQueryResult($rows);
        $standardInputReader = $this->mockStandardInputReader('');

        $this->buildCommandTesterForCommand($connectionFactory, $standardInputReader);

        $input = [
            'command' => $this->command->getName(),
            '--user' => 'root',
            '--password' => '',
            '--database' => 'homestead',
            '--host' => '127.0.0.1',
            '--port' => '3306',
            '--driver' => 'pdo_mysql',
            'sql' => 'select * from table_name',
        ];

        $this->commandTester->execute($input);

        $this->commandShouldDisplay(<<<'EOT'
+---------+---------+
| column1 | column2 |
+---------+---------+
| value 1 | value 2 |
+---------+---------+
EOT
            , $this->commandTester->getDisplay());
    }

    /** @test */
    public function it_should_execute_a_sql_query_passed_from_standard_input()
    {
        $rows = [
            [
                "column1" => "value 1",
                "column2" => "value 2",
            ],
        ];

        $connectionFactory = $this->mockConnectionFactoryWithQueryResult($rows);
        $standardInputReader = $this->mockStandardInputReader('slq from standard input');

        $this->buildCommandTesterForCommand($connectionFactory, $standardInputReader);

        $input = [
            'command' => $this->command->getName(),
            '--user' => 'root',
            '--password' => '',
            '--database' => 'homestead',
            '--host' => '127.0.0.1',
            '--port' => '3306',
            '--driver' => 'pdo_mysql',
        ];

        $this->commandTester->execute($input);

        $this->commandShouldDisplay(<<<'EOT'
+---------+---------+
| column1 | column2 |
+---------+---------+
| value 1 | value 2 |
+---------+---------+
EOT
            , $this->commandTester->getDisplay());
    }

    /** @test */
    public function it_should_execute_a_sql_query_in_interactive_mode()
    {
        $rows = [
            [
                "column1" => "value 1",
                "column2" => "value 2",
            ],
        ];

        $connectionFactory = $this->mockConnectionFactoryWithQueryResult($rows);
        $standardInputReader = $this->mockStandardInputReader('show tables;');

        $this->buildCommandTesterForCommand($connectionFactory, $standardInputReader);

        $input = [
            'command' => $this->command->getName(),
            '--interactive' => 'true',
            '--user' => 'root',
            '--password' => '',
            '--database' => 'homestead',
            '--host' => '127.0.0.1',
            '--port' => '3306',
            '--driver' => 'pdo_mysql',
        ];

        $this->commandTester->execute($input);

        $this->commandShouldDisplay(<<<'EOT'
Welcome to interactive php SQL shell. Type 'quit' or 'exit' to exit.
>+---------+---------+
| column1 | column2 |
+---------+---------+
| value 1 | value 2 |
+---------+---------+
>Bye.

EOT
            , $this->commandTester->getDisplay());
    }

    /** @test */
    public function it_should_show_a_message_when_the_result_is_empty()
    {
        $rows = [];

        $connectionFactory = $this->mockConnectionFactoryWithQueryResult($rows);
        $standardInputReader = $this->mockStandardInputReader('show tables;');

        $this->buildCommandTesterForCommand($connectionFactory, $standardInputReader);

        $input = [
            'command' => $this->command->getName(),
            '--interactive' => 'true',
            '--user' => 'root',
            '--password' => '',
            '--database' => 'homestead',
            '--host' => '127.0.0.1',
            '--port' => '3306',
            '--driver' => 'pdo_mysql',
        ];

        $this->commandTester->execute($input);

        $this->commandShouldDisplay(<<<'EOT'
Welcome to interactive php SQL shell. Type 'quit' or 'exit' to exit.
>Empty result.
>Bye.

EOT
            , $this->commandTester->getDisplay());
    }

    /** @test */
    public function it_should_allow_to_exit_from_interactive_mode()
    {
        $connectionFactory = $this->mockConnectionFactory();

        $standardInputReader = $this->mockStandardInputReader('quit');

        $this->buildCommandTesterForCommand($connectionFactory, $standardInputReader);

        $input = [
            'command' => $this->command->getName(),
            '--interactive' => 'true',
            '--user' => 'root',
            '--password' => '',
            '--database' => 'homestead',
            '--host' => '127.0.0.1',
            '--port' => '3306',
            '--driver' => 'pdo_mysql',
        ];

        $this->commandTester->execute($input);

        $this->commandShouldDisplay(<<<'EOT'
Welcome to interactive php SQL shell. Type 'quit' or 'exit' to exit.
>Bye.

EOT
            , $this->commandTester->getDisplay());
    }

    /** @test */
    public function it_should_print_an_error_message_if_it_can_not_connect_to_database()
    {
        $rows = [
            [
                "column1" => "value 1",
                "column2" => "value 2",
            ],
        ];

        $connectionFactory = $this->mockConnectionFactoryWithInvalidConnection('can not connect error');
        $standardInputReader = $this->mockStandardInputReader();

        $this->buildCommandTesterForCommand($connectionFactory, $standardInputReader);

        $input = [
            'command' => $this->command->getName(),
            '--user' => 'root',
            '--password' => '',
            '--database' => 'homestead',
            '--host' => '127.0.0.1',
            '--port' => '3306',
            '--driver' => 'pdo_mysql',
        ];

        $this->commandTester->execute($input);

        $this->commandShouldDisplay('can not connect error', $this->commandTester->getDisplay());
    }

    /**
     * @param $connectionFactory
     * @param $standardInputReader
     */
    protected function buildCommandTesterForCommand($connectionFactory, $standardInputReader)
    {
        $this->command = new PhpSqlCommand($connectionFactory, $standardInputReader);
        $this->application->add($this->command);
        $this->commandTester = new CommandTester($this->command);
    }
}
