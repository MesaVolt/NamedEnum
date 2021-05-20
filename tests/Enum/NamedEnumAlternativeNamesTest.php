<?php
declare(strict_types=1);

namespace Mesavolt\Tests\Enum;


use Mesavolt\Tests\Fixture\IncompleteNamedEnum;
use PHPUnit\Framework\TestCase;
use Mesavolt\Tests\Fixture\TestEnum;

final class NamedEnumAlternativeNamesTest extends TestCase
{
    public function testCanGetNameFromValue(): void
    {
        self::assertEquals('ALTERNATIVE NAME 1', TestEnum::getName(TestEnum::VALUE_1, TestEnum::$ALTERNATIVE_NAMES));
        self::assertEquals('ALTERNATIVE NAME 2', TestEnum::getName(TestEnum::VALUE_2, TestEnum::$ALTERNATIVE_NAMES));
        self::assertEquals('ALTERNATIVE NAME STRING', TestEnum::getName(TestEnum::VALUE_STRING, TestEnum::$ALTERNATIVE_NAMES));
    }

    public function testCanGetNames(): void
    {
        self::assertEquals(
            [
                TestEnum::VALUE_1 => 'ALTERNATIVE NAME 1',
                TestEnum::VALUE_2 => 'ALTERNATIVE NAME 2',
                TestEnum::VALUE_STRING => 'ALTERNATIVE NAME STRING'
            ],
            TestEnum::getNames(TestEnum::$ALTERNATIVE_NAMES)
        );
    }

    public function testCanGetAllNames(): void
    {
        $names = ['ALTERNATIVE NAME 1', 'ALTERNATIVE NAME 2', 'ALTERNATIVE NAME STRING'];
        self::assertSameSize($names, TestEnum::names(TestEnum::$ALTERNATIVE_NAMES));

        foreach($names as $name) {
            self::assertContains($name, TestEnum::names(TestEnum::$ALTERNATIVE_NAMES));
        }
    }

    public function testCanGetChoices(): void
    {
        $choices = [
            'ALTERNATIVE NAME 1' => 1,
            'ALTERNATIVE NAME 2' => 2,
            'ALTERNATIVE NAME STRING' => 'string'
        ];
        self::assertSameSize($choices, TestEnum::choices(TestEnum::$ALTERNATIVE_NAMES));

        foreach($choices as $name => $value) {
            self::assertEquals($name, TestEnum::getName($value, TestEnum::$ALTERNATIVE_NAMES));
        }
    }

    public function testReturnNullForNonExistingValue(): void
    {
        self::assertNull(TestEnum::getName(-1, TestEnum::$ALTERNATIVE_NAMES));
    }

    public function testCanGetArrays(): void
    {
        $arrays = [
            ['name' => 'ALTERNATIVE NAME 1', 'value' => 1],
            ['name' => 'ALTERNATIVE NAME 2', 'value' => 2],
            ['name' => 'ALTERNATIVE NAME STRING', 'value' => 'string'],
        ];

        self::assertEquals(TestEnum::arrays(TestEnum::$ALTERNATIVE_NAMES), $arrays);
    }
}
