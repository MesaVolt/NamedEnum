<?php
declare(strict_types=1);

namespace Mesavolt\Tests\Enum;


use Mesavolt\Tests\Fixture\IncompleteNamedEnum;
use PHPUnit\Framework\TestCase;
use Mesavolt\Tests\Fixture\TestEnum;

final class NamedEnumTest extends TestCase
{
    public function testCanGetNameFromValue(): void
    {
        self::assertEquals('NAME 1', TestEnum::getName(TestEnum::VALUE_1));
        self::assertEquals('NAME 2', TestEnum::getName(TestEnum::VALUE_2));
        self::assertEquals('NAME STRING', TestEnum::getName(TestEnum::VALUE_STRING));
    }

    public function testCanGetNames(): void
    {
        self::assertEquals(
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
        self::assertSameSize($names, TestEnum::names());

        foreach($names as $name) {
            self::assertContains($name, TestEnum::names());
        }
    }

    public function testCanGetAllValues(): void
    {
        $values = [1, 2, 'string'];
        self::assertSameSize($values, TestEnum::values());

        foreach($values as $value) {
            self::assertContains($value, TestEnum::values());
        }
    }

    public function testCanGetChoices(): void
    {
        $choices = [
            'NAME 1' => 1,
            'NAME 2' => 2,
            'NAME STRING' => 'string'
        ];
        self::assertSameSize($choices, TestEnum::choices());

        foreach($choices as $name => $value) {
            self::assertEquals($name, TestEnum::getName($value));
        }
    }

    public function testCanGetConstants(): void
    {
        $constants = [
            'VALUE_1' => 1,
            'VALUE_2' => 2,
            'VALUE_STRING' => 'string'
        ];
        self::assertSameSize($constants, TestEnum::constants());

        foreach($constants as $constName => $value) {
            self::assertEquals($value, TestEnum::constants()[$constName]);
        }
    }

    public function testReturnNullForNonExistingValue(): void
    {
        self::assertNull(TestEnum::getName(-1));
    }

    public function testCanGetArrays(): void
    {
        $arrays = [
            ['name' => 'NAME 1', 'value' => 1],
            ['name' => 'NAME 2', 'value' => 2],
            ['name' => 'NAME STRING', 'value' => 'string'],
        ];

        self::assertEquals(TestEnum::arrays(), $arrays);
    }

    public function testEnsureValid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument provided to Mesavolt\Tests\Enum\NamedEnumTest->testEnsureValid - expected one of "1, 2, string", got "banana"');

        TestEnum::ensureValid('banana');
    }

    public function dataProvider_ensureValidExceptionMessage(): array
    {
        return [
            [null, 'null'],
            [false, 'false'],
            [true, 'true'],
            [new \Exception(), \Exception::class],
        ];
    }

    /**
     * @dataProvider dataProvider_ensureValidExceptionMessage
     */
    public function testEnsureValidExceptionMessage($value, string $expectedStringRepresentation): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument provided to Mesavolt\Tests\Enum\NamedEnumTest->testEnsureValidExceptionMessage - expected one of "1, 2, string", got "'.$expectedStringRepresentation.'"');
        TestEnum::ensureValid($value);
    }

    public function testEnsureValidWithNonNullable(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument provided to Mesavolt\Tests\Enum\NamedEnumTest->testEnsureValidWithNonNullable - expected one of "1, 2, string", got "null"');

        TestEnum::ensureValid(null, false);
    }

    public function testEnsureValidWithNullable(): void
    {
        TestEnum::ensureValid(null, true);
        self::assertTrue(true);
    }

    public function testEnsureValidWithStrictCheck(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument provided to Mesavolt\Tests\Enum\NamedEnumTest->testEnsureValidWithStrictCheck - expected one of "1, 2, string", got "1"');

        TestEnum::ensureValid('1', false, true);
    }

    public function testEnsureValidWithNonStrictCheck(): void
    {
        TestEnum::ensureValid('1', false, false);
        self::assertTrue(true);
    }

    public function testGettingValuesDoesntRequireName(): void
    {
        $values = IncompleteNamedEnum::values();
        self::assertCount(3, $values);
    }
}
