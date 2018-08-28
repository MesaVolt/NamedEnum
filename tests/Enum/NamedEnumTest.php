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

    public function testCanGetArrays(): void
    {
        $arrays = [
            ['name' => 'NAME 1', 'value' => 1],
            ['name' => 'NAME 2', 'value' => 2],
            ['name' => 'NAME STRING', 'value' => 'string'],
        ];

        $this->assertEquals(TestEnum::arrays(), $arrays);
    }

    public function testEnsureValid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument provided to Mesavolt\Tests\Enum\NamedEnumTest::testEnsureValid - expected one of "1, 2, string", got "banana"');

        TestEnum::ensureValid('banana', __METHOD__);
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
        $this->expectExceptionMessage('Invalid argument provided to Mesavolt\Tests\Enum\NamedEnumTest::testEnsureValidExceptionMessage - expected one of "1, 2, string", got "'.$expectedStringRepresentation.'"');
        TestEnum::ensureValid($value, __METHOD__);
    }

    public function testEnsureValidWithNonNullable(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument provided to Mesavolt\Tests\Enum\NamedEnumTest::testEnsureValidWithNonNullable - expected one of "1, 2, string", got "null"');

        TestEnum::ensureValid(null, __METHOD__);
    }

    public function testEnsureValidWithNullable(): void
    {
        TestEnum::ensureValid(null, __METHOD__, true);
        $this->assertTrue(true);
    }

    public function testEnsureValidWithStrictCheck(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid argument provided to Mesavolt\Tests\Enum\NamedEnumTest::testEnsureValidWithStrictCheck - expected one of "1, 2, string", got "1"');

        TestEnum::ensureValid('1', __METHOD__, false, true);
    }

    public function testEnsureValidWithNonStrictCheck(): void
    {
        TestEnum::ensureValid('1', __METHOD__, false, false);
        $this->assertTrue(true);
    }
}
