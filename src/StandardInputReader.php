<?php

namespace PhpSql;

class StandardInputReader
{
    public function readLine()
    {
        // TODO: it seems to be impossible non blocking read from standard input in PHP.
        return fgets(STDIN);
    }
}