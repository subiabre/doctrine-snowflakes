<?php

namespace Subiabre\Tests\LockDirectoryTest;

use PHPUnit\Framework\TestCase;
use Subiabre\SnowflakeGenerator;

class LockDirectoryTest extends TestCase
{
    /*
        This test is located at a different folder to make sure PHP builds path at project root
        regardless of the path at which `SnowflakeGenerator::__construct()` is called at
    */
    public function testLockdirDefaultPathIsAtProjectRoot()
    {
        $generator = new SnowflakeGenerator();
        $lockdir   = sprintf('%s%s%s', \dirname(__DIR__, 2), DIRECTORY_SEPARATOR, 'lock');

        $this->assertEquals($lockdir, $generator->lockdir);
    }
}
