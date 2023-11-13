<?php

namespace Subiabre\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use Subiabre\SnowflakeGenerator;
use PHPUnit\Framework\TestCase;

class SnowflakeGeneratorTest extends TestCase
{
    public static function provideIds(): array
    {
        $array = array_fill(0, 100, null);
        $generator = new SnowflakeGenerator();

        return array_fill(0, 100, [array_map(function() use ($generator) {
            return $generator->new();
        }, $array)]);
    }

    #[DataProvider('provideIds')]
    public function testNotConcurrent($ids)
    {
        $this->assertCount(100, $ids);

        foreach ($ids as $id) {
            $this->assertEquals(1, count(array_keys($ids, $id)));
        }
    }
}
