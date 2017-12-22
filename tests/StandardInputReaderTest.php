<?php

namespace Test\PhpSql;

use PhpSql\StandardInputReader;

class StandardInputReaderTest extends CommandTestCase
{
    /** @test */
    public function it_should_delegate_to_php_in_build_function()
    {
        // TODO: override function to test method?
        // http://php.net/manual/en/function.override-function.php
        $sut = new StandardInputReader();
        $this->assertInstanceOf(StandardInputReader::class, $sut);
    }
}
