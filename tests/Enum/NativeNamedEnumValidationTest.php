<?php

declare(strict_types=1);

namespace Mesavolt\Tests\Enum;

use Mesavolt\Tests\Fixture\ForeignIntEnum;
use Mesavolt\Tests\Fixture\ForeignStringEnum;
use Mesavolt\Tests\Fixture\NativeIntEnum;
use Mesavolt\Tests\Fixture\NativeStringEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class NativeNamedEnumValidationTest extends TestCase
{
    public static function dataProvider_enumClasses(): array
    {
        return [
            'integer backing' => [NativeIntEnum::class],
            'string backing' => [NativeStringEnum::class],
        ];
    }

    #[DataProvider('dataProvider_enumClasses')]
    /** @var class-string<NamedEnumInterface> $class */
    public function testBackingValuesAndCasesAreValid(string $class): void
    {
        foreach ($class::cases() as $case) {
            self::assertTrue($class::isValid($case->value));
            self::assertTrue($class::isValid($case));
            $class::ensureValid($case->value);
            $class::ensureValid($case);
        }

        self::assertFalse($class::isValid('unknown'));
        self::assertFalse($class::isValid(null));
        self::assertTrue($class::isValid(null, nullable: true));
        $class::ensureValid(null, nullable: true);
    }

    public function testStrictAndNonStrictValidation(): void
    {
        self::assertFalse(NativeIntEnum::isValid('1'));
        self::assertFalse(NativeIntEnum::isValid(1.0));
        self::assertTrue(NativeIntEnum::isValid('1', nullable: false, strictCheck: false));
        self::assertTrue(NativeIntEnum::isValid(1.0, nullable: false, strictCheck: false));
        NativeIntEnum::ensureValid('1', nullable: false, strictCheck: false);
    }

    public static function provideForeignCases(): array
    {
        return [
            'integer case' => [NativeIntEnum::class, ForeignIntEnum::VALUE_1],
            'string case' => [NativeStringEnum::class, ForeignStringEnum::VALUE_1],
        ];
    }

    #[DataProvider('provideForeignCases')]
    /** @var class-string<NamedEnumInterface> $class */
    public function testForeignCasesAreRejectedEvenWithMatchingBackingValues(string $class, \BackedEnum $case): void
    {
        self::assertFalse($class::isValid($case));
        self::assertFalse($class::isValid($case, nullable: true, strictCheck: false));

        $this->expectException(\InvalidArgumentException::class);
        $class::ensureValid($case, nullable: true, strictCheck: false);
    }

    public static function dataProvider_testEnsureValidExceptionIncludesCallerAndValue(): array
    {
        return [
            'unknown string' => ['banana', 'banana'],
            'null' => [null, 'null'],
            'false' => [false, 'false'],
            'true' => [true, 'true'],
            'array' => [[1], 'array'],
            'object' => [new \stdClass(), 'stdClass'],
        ];
    }

    #[DataProvider('dataProvider_testEnsureValidExceptionIncludesCallerAndValue')]
    /** @var class-string<NamedEnumInterface> $class */
    public function testEnsureValidExceptionIncludesCallerAndValue(mixed $value, string $representation): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageIsOrContains(sprintf(
            'Invalid argument provided to %s->testEnsureValidExceptionIncludesCallerAndValue - expected one of "1, 2, 3", got "%s"',
            self::class,
            $representation,
        ));

        NativeIntEnum::ensureValid($value);
    }

    public function testEnsureValidWithStrictCheck(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        NativeIntEnum::ensureValid('1');
    }
}
