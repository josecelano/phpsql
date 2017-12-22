<?php

namespace PhpSql;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PhpSqlCommand extends Command
{
    /** @var  ConnectionFactory */
    private $connectionFactory;

    /** @var  StandardInputReader */
    private $standardInputReader;

    /**
     * PhpSqlCommand constructor.
     * @param ConnectionFactory $connectionFactory
     * @param StandardInputReader $standardInputReader
     */
    public function __construct(ConnectionFactory $connectionFactory, StandardInputReader $standardInputReader)
    {
        $this->connectionFactory = $connectionFactory;
        $this->standardInputReader = $standardInputReader;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('phpsql')
            ->setDescription('Execute SQL from console for all DB supported by doctrine DBAL.')
            ->setHelp('This command is like mysql console client but using PHP with Doctrine DBAL.')
            ->addOption('user', 'u', InputOption::VALUE_REQUIRED)
            ->addOption('database', 'D', InputOption::VALUE_REQUIRED)
            ->addOption('password', 'p', InputOption::VALUE_REQUIRED)
            ->addOption('host', 'H', InputOption::VALUE_REQUIRED, 'Host', '127.0.0.1')
            ->addOption('port', 'P', InputOption::VALUE_REQUIRED, 'Port', 3306)
            ->addOption('driver', 'd', InputOption::VALUE_REQUIRED, 'Driver', 'pdo_mysql')
            ->addOption('interactive', 'i', InputOption::VALUE_OPTIONAL, 'Interactive mode', 'false')
            ->addArgument('sql', InputArgument::OPTIONAL, 'SQL sentence');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $connection = $this->connectionFactory->getInstance($input->getOptions());

        $this->testConnection($output, $connection);

        $queryPrinter = new QueryPrinter($connection, $output);

        $sql = $input->getArgument('sql');
        if ($sql) {
            $queryPrinter->printToConsole($sql);
            return;
        }

        $interactiveMode = $input->getOption('interactive') == 'false' ? false : true;

        if ($interactiveMode) {
            $this->executeInteractiveMode($queryPrinter, $output);
        } else {
            $this->readDataFromStandardInput($queryPrinter);
        }
    }

    /**
     * @param OutputInterface $output
     * @param $connection
     */
    private function testConnection(OutputInterface $output, $connection)
    {
        $result = (new ConnectionTester())->test($connection);

        if ($result !== true) {
            $output->writeln("<error>$result</error>");
        }
    }

    /**
     * @param QueryPrinter $queryPrinter
     * @param OutputInterface $output
     * @internal param $interactiveMode
     */
    private function executeInteractiveMode(QueryPrinter $queryPrinter, OutputInterface $output)
    {
        $this->writeWelcomeMessage($output);

        while ($line = $this->standardInputReader->readLine()) {

            if ($this->lineContainsExitCommand($line)) {
                break;
            }

            $queryPrinter->printToConsole($line);

            $this->writeShellPromptSymbol($output);
        }

        $this->writeByeMessage($output);
    }

    /**
     * @param QueryPrinter $queryPrinter
     */
    private function readDataFromStandardInput(QueryPrinter $queryPrinter)
    {
        while ($line = $this->standardInputReader->readLine()) {
            $queryPrinter->printToConsole($line);
        }
    }

    /**
     * @param OutputInterface $output
     */
    private function writeWelcomeMessage(OutputInterface $output)
    {
        $output->writeln("Welcome to interactive php SQL shell. Type 'quit' or 'exit' to exit.");
        $this->writeShellPromptSymbol($output);
    }

    /**
     * @param OutputInterface $output
     */
    private function writeByeMessage(OutputInterface $output)
    {
        $output->writeln("Bye.");
    }

    /**
     * @param OutputInterface $output
     */
    private function writeShellPromptSymbol(OutputInterface $output)
    {
        $output->write(">");
    }

    /**
     * @param $line
     * @return bool
     */
    private function lineContainsExitCommand($line)
    {
        return trim($line) == 'quit' || trim($line) == 'exit';
    }
}
