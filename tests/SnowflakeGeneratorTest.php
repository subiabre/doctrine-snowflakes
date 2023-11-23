<?php

namespace Subiabre\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use Subiabre\SnowflakeGenerator;
use PHPUnit\Framework\TestCase;

class SnowflakeGeneratorTest extends TestCase
{
    private const BATCH_SIZE = 300;

    public function testLockdirThrowsExceptionOnPathNotFound()
    {
        $this->expectException(\Exception::class);

        new SnowflakeGenerator(lockdir: 'nopath');
    }

    public function testLockdirThrowsExceptionOnPathNotDirectory()
    {
        $this->expectException(\Exception::class);

        new SnowflakeGenerator(lockdir: __FILE__);
    }

    public function testLockdirLoadsDirectory()
    {
        $generator = new SnowflakeGenerator(lockdir: __DIR__);

        $this->assertSame(__DIR__, $generator->lockdir);
    }

    private static function getBatchSize(): int
    {
        $size = getenv('BATCH_SIZE');

        return $size
            ? $size
            : self::BATCH_SIZE;
    }

    public static function provideIdsBatches(): array
    {
        $array = array_fill(0, self::getBatchSize(), null);
        $generator = new SnowflakeGenerator();

        return array_fill(0, 10, [array_map(function() use ($generator) {
            return $generator->new();
        }, $array)]);
    }

    #[DataProvider('provideIdsBatches')]
    public function testNotConcurrentInBatch($ids)
    {
        foreach ($ids as $id) {
            $this->assertEquals(1, count(array_keys($ids, $id)));
        }
    }
}
