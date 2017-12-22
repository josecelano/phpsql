<?php

namespace PhpSql;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

class QueryPrinter
{
    /** @var  Connection */
    private $connection;

    /** @var  OutputInterface */
    private $output;

    /**
     * QueryPrinter constructor.
     * @param Connection $connection
     * @param OutputInterface $output
     */
    public function __construct(Connection $connection, OutputInterface $output = null)
    {
        $this->connection = $connection;
        $this->output = $output;
    }

    /**
     * @param string $sql
     */
    public function printToConsole($sql)
    {
        if (trim($sql) == '') {
            return;
        }

        $stmt = $this->executeQuerySafely($sql);

        $this->renderQueryResult($stmt);
    }

    /**
     * @param $sql
     * @return \Doctrine\DBAL\Driver\Statement|null
     */
    private function executeQuerySafely($sql)
    {
        $stmt = null;

        try {
            $stmt = $this->connection->query($sql);
        } catch (\Exception $exception) {
            $this->output->writeln(sprintf('<error>%s</error>', $exception->getMessage()));
        }

        return $stmt;
    }

    /**
     * @param \Doctrine\DBAL\Driver\Statement|null $stmt
     */
    private function renderQueryResult($stmt)
    {
        if (!$stmt) {
            return;
        }

        if ($stmt->rowCount() == 0) {
            $this->output->writeln('<info>Empty result.</info>');
            return;
        }

        $table = new Table($this->output);

        $this->addRowsToTable($stmt, $table);

        $table->render();
    }

    /**
     * @param \Doctrine\DBAL\Driver\Statement|null $stmt
     * @param $table
     * @return int
     */
    private function addRowsToTable($stmt, Table $table)
    {
        $cont = 1;

        while ($row = $stmt->fetch()) {
            if ($cont == 1) {
                $table->setHeaders(array_keys($row));
            }
            $table->addRow(array_values($row));
            $cont++;
        }

        return $cont;
    }
}
