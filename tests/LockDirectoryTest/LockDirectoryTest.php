<?php

namespace Subiabre\Tests\LockDirectoryTest;

use PHPUnit\Framework\TestCase;
use Subiabre\SnowflakeGenerator;

class LockDirectoryTest extends TestCase
{
    public function testLockDirectoryIsAtProjectRoot()
    {
        $generator = new SnowflakeGenerator();
        $lockdir   = sprintf('%s%s%s', \dirname(__DIR__, 2), DIRECTORY_SEPARATOR, 'lock');

        $this->assertEquals($lockdir, $generator->lockdir);
    }
}
