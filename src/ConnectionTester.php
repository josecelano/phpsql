<?php

namespace PhpSql;

class ConnectionTester
{
    public function test(Connection $connection)
    {
        try {
            $connection->connect();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return true;
    }
}
