<?php

namespace PhpSql;

interface Connection
{
    public function connect();

    public function query($sql);

    public function getDatabase();

    public function getUser();

    public function getPassword();

    public function getHost();

    public function getPort();

    public function getDriver();

    public function getParams();
}
