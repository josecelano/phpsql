<?php

require __DIR__ . '/vendor/autoload.php';

use PhpSql\ConnectionFactory;
use PhpSql\PhpSqlCommand;
use PhpSql\StandardInputReader;
use Symfony\Component\Console\Application;

(new Application('phpsql', '1.0.0'))
    ->add(new PhpSqlCommand(new ConnectionFactory(), new StandardInputReader()))
    ->getApplication()
    ->setDefaultCommand('phpsql', true)
    ->run();
