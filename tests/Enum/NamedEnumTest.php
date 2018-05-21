<?php
declare(strict_types=1);

namespace Mesavolt\Tests\Enum;


use PHPUnit\Framework\TestCase;
use Mesavolt\Tests\Fixture\TestEnum;

final class NamedEnumTest extends TestCase
{
    public function testCanGetNameFromValue(): void
    {
        $this->assertEquals('NAME 1', TestEnum::getName(TestEnum::VALUE_1));
        $this->assertEquals('NAME 2', TestEnum::getName(TestEnum::VALUE_2));
        $this->assertEquals('NAME STRING', TestEnum::getName(TestEnum::VALUE_STRING));
    }

    public function testCanGetNames(): void
    {
        $this->assertEquals(
            [
                TestEnum::VALUE_1 => 'NAME 1',
                TestEnum::VALUE_2 => 'NAME 2',
                TestEnum::VALUE_STRING => 'NAME STRING'
            ],
            TestEnum::getNames()
        );
    }

    public function testCanGetAllNames(): void
    {
        $names = ['NAME 1', 'NAME 2', 'NAME STRING'];
        $this->assertSameSize($names, TestEnum::names());

        foreach($names as $name) {
            $this->assertContains($name, TestEnum::names());
        }
    }

    public function testCanGetAllValues(): void
    {
        $values = [1, 2, 'string'];
        $this->assertSameSize($values, TestEnum::values());

        foreach($values as $value) {
            $this->assertContains($value, TestEnum::values());
        }
    }

    public function testCanGetChoices(): void
    {
        $choices = [
            'NAME 1' => 1,
            'NAME 2' => 2,
            'NAME STRING' => 'string'
        ];
        $this->assertSameSize($choices, TestEnum::choices());

        foreach($choices as $name => $value) {
            $this->assertEquals($name, TestEnum::getName($value));
        }
    }

    public function testCanGetConstants(): void
    {
        $constants = [
            'VALUE_1' => 1,
            'VALUE_2' => 2,
            'VALUE_STRING' => 'string'
        ];
        $this->assertSameSize($constants, TestEnum::constants());

        foreach($constants as $constName => $value) {
            $this->assertEquals($value, TestEnum::constants()[$constName]);
        }
    }

    public function testReturnNullForNonExistingValue(): void
    {
        $this->assertNull(TestEnum::getName(-1));
    }

    public function testCanGetArrays()
    {
        $arrays = [
            ['name' => 'NAME 1', 'value' => 1],
            ['name' => 'NAME 2', 'value' => 2],
            ['name' => 'NAME STRING', 'value' => 'string'],
        ];

        $this->assertEquals(TestEnum::arrays(), $arrays);
    }
}
