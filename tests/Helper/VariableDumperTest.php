<?php

namespace Mesavolt\Tests\Helper;

use PHPUnit\Framework\Attributes\DataProvider;
use Mesavolt\Helper\VariableDumper;
use PHPUnit\Framework\TestCase;

class VariableDumperTest extends TestCase
{
    public static function dataProvider_dump(): array
    {
        return [
            // boolean
            [true, 'true'],
            [false, 'false'],
            // integer
            [0, '0'],
            // double
            [1.1, '1.1'],
            // string
            ['str', 'str'],
            // array
            [[1, 2, 3], 'array'],
            // object
            [new \stdClass(), 'stdClass'],
            [new \Exception(), \Exception::class],
            // resouruce
            [fopen(__FILE__, 'rb'), 'resource']
        ];
    }

    #[DataProvider('dataProvider_dump')]
    public function test_dump($input, string $expectedOutput): void
    {
        self::assertSame(
            $expectedOutput,
            VariableDumper::dump($input)
        );
    }
}
