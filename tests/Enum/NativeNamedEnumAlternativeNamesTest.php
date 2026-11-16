<?php

declare(strict_types=1);

namespace Mesavolt\Tests\Enum;

use Mesavolt\Tests\Fixture\NativeIntEnum;
use Mesavolt\Tests\Fixture\NativeStringEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class NativeNamedEnumAlternativeNamesTest extends TestCase
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
    public function testCanGetNameFromValueAndCase(string $class): void
    {
        $alternativeNames = [$class::VALUE_1->value => 'Alternative first name'];

        self::assertSame('Alternative first name', $class::getName($class::VALUE_1->value, $alternativeNames));
        self::assertSame('Alternative first name', $class::getName($class::VALUE_1, $alternativeNames));
        self::assertNotNull($class::getName($class::VALUE_2, $alternativeNames));
        self::assertNull($class::getName('unknown', $alternativeNames));
        self::assertSame($class::VALUE_1->name(), $class::getName($class::VALUE_1));
    }

    #[DataProvider('dataProvider_enumClasses')]
    /** @var class-string<NamedEnumInterface> $class */
    public function testAlternativeNamesReplaceDefaults(string $class): void
    {
        $unnamed = $class::UNNAMED->value;
        $one = $class::VALUE_1->value;
        $alternativeNames = [$unnamed => 'Alt unnamed', $one => 'Alt one'];
        $name2 = $class::VALUE_2->name();

        self::assertSame([$one => 'Alt one', $class::VALUE_2->value => $name2, $unnamed => 'Alt unnamed'], $class::getNames($alternativeNames));
        self::assertSame(['Alt one', $name2, 'Alt unnamed'], $class::names($alternativeNames));
        self::assertSame(['Alt one' => $class::VALUE_1, $name2 => $class::VALUE_2, 'Alt unnamed' => $class::UNNAMED], $class::choices($alternativeNames));
        self::assertSame([
            ['name' => 'Alt one', 'value' => $one],
            ['name' => $name2, 'value' => $class::VALUE_2->value],
            ['name' => 'Alt unnamed', 'value' => $unnamed],
        ], $class::arrays($alternativeNames));
    }

    #[DataProvider('dataProvider_enumClasses')]
    /** @var class-string<NamedEnumInterface> $class */
    public function testEmptyAlternativeNamesUseDefaults(string $class): void
    {
        self::assertSame($class::getName($class::VALUE_1), $class::getName($class::VALUE_1, []));
        self::assertSame($class::getNames(), $class::getNames([]));
        self::assertSame($class::names(), $class::names([]));
        self::assertSame($class::choices(), $class::choices([]));
        self::assertSame($class::arrays(), $class::arrays([]));
    }

    public function testAlternativeNamesCannotMakeAnUnknownValueAValidLookup(): void
    {
        self::assertNull(NativeIntEnum::getName(99, [99 => 'Unknown']));
        self::assertNull(NativeStringEnum::getName('unknown', ['unknown' => 'Unknown']));
    }
}
