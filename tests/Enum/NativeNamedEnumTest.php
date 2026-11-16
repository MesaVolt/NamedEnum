<?php

declare(strict_types=1);

namespace Mesavolt\Tests\Enum;

use Mesavolt\Enum\ChoicesHelper;
use Mesavolt\Enum\NamedEnumInterface;
use Mesavolt\Tests\Fixture\ForeignIntEnum;
use Mesavolt\Tests\Fixture\ForeignStringEnum;
use Mesavolt\Tests\Fixture\NativeEdgeCaseEnum;
use Mesavolt\Tests\Fixture\NativeEnumWithoutAttributes;
use Mesavolt\Tests\Fixture\NativeIntEnum;
use Mesavolt\Tests\Fixture\NativeStringEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class NativeNamedEnumTest extends TestCase
{
    private const array EXPECTED = [
        NativeIntEnum::class => [
            'constants' => ['VALUE_1' => 1, 'VALUE_2' => 2, 'UNNAMED' => 3],
            'names' => [1 => 'NAME 1', 2 => 'NAME 2', 3 => 'UNNAMED'],
            'choices' => ['NAME 1' => NativeIntEnum::VALUE_1, 'NAME 2' => NativeIntEnum::VALUE_2, 'UNNAMED' => NativeIntEnum::UNNAMED],
            'arrays' => [
                ['name' => 'NAME 1', 'value' => 1],
                ['name' => 'NAME 2', 'value' => 2],
                ['name' => 'UNNAMED', 'value' => 3],
            ],
        ],
        NativeStringEnum::class => [
            'constants' => ['VALUE_1' => 'one', 'VALUE_2' => 'two', 'UNNAMED' => 'three'],
            'names' => ['one' => 'STRING NAME 1', 'two' => 'STRING NAME 2', 'three' => 'UNNAMED'],
            'choices' => ['STRING NAME 1' => NativeStringEnum::VALUE_1, 'STRING NAME 2' => NativeStringEnum::VALUE_2, 'UNNAMED' => NativeStringEnum::UNNAMED],
            'arrays' => [
                ['name' => 'STRING NAME 1', 'value' => 'one'],
                ['name' => 'STRING NAME 2', 'value' => 'two'],
                ['name' => 'UNNAMED', 'value' => 'three'],
            ],
        ],
    ];

    public static function dataProvider_enumClasses(): array
    {
        return [
            'integer backing' => [NativeIntEnum::class],
            'string backing' => [NativeStringEnum::class],
        ];
    }

    #[DataProvider('dataProvider_enumClasses')]
    /** @var class-string<NamedEnumInterface> $class */
    public function testCanGetNameFromBackingValue(string $class): void
    {
        foreach (self::EXPECTED[$class]['names'] as $value => $name) {
            self::assertSame($name, $class::getName($value));
        }
    }

    #[DataProvider('dataProvider_enumClasses')]
    /** @var class-string<NamedEnumInterface> $class */
    public function testCanGetNameFromCase(string $class): void
    {
        foreach ($class::cases() as $case) {
            self::assertInstanceOf(NamedEnumInterface::class, $case);
            self::assertSame(self::EXPECTED[$class]['names'][$case->value], $case->name());
            self::assertSame($case->name(), $class::getName($case));
        }
    }

    #[DataProvider('dataProvider_enumClasses')]
    /** @var class-string<NamedEnumInterface> $class */
    public function testCanGetNames(string $class): void
    {
        self::assertSame(self::EXPECTED[$class]['names'], $class::getNames());
    }

    #[DataProvider('dataProvider_enumClasses')]
    /** @var class-string<NamedEnumInterface> $class */
    public function testCanGetAllNames(string $class): void
    {
        self::assertSame(array_values(self::EXPECTED[$class]['names']), $class::names());
    }

    #[DataProvider('dataProvider_enumClasses')]
    /** @var class-string<NamedEnumInterface> $class */
    public function testCanGetAllValues(string $class): void
    {
        self::assertSame(array_values(self::EXPECTED[$class]['constants']), $class::values());
    }

    #[DataProvider('dataProvider_enumClasses')]
    /** @var class-string<NamedEnumInterface> $class */
    public function testCanGetConstants(string $class): void
    {
        self::assertSame(self::EXPECTED[$class]['constants'], $class::constants());
    }

    #[DataProvider('dataProvider_enumClasses')]
    /** @var class-string<NamedEnumInterface> $class */
    public function testCanGetChoices(string $class): void
    {
        self::assertSame(self::EXPECTED[$class]['choices'], $class::choices());
    }

    #[DataProvider('dataProvider_enumClasses')]
    /** @var class-string<NamedEnumInterface> $class */
    public function testCanGetArrays(string $class): void
    {
        self::assertSame(self::EXPECTED[$class]['arrays'], $class::arrays());
    }

    #[DataProvider('dataProvider_enumClasses')]
    /** @var class-string<NamedEnumInterface> $class */
    public function testReturnNullForNonExistingValue(string $class): void
    {
        self::assertNull($class::getName('unknown'));
        self::assertNull($class::getName(-1));
    }

    #[DataProvider('dataProvider_enumClasses')]
    /** @var class-string<NamedEnumInterface> $class */
    public function testCanFilterChoices(string $class): void
    {
        $choices = self::EXPECTED[$class]['choices'];
        $firstValue = $class::VALUE_1;

        self::assertSame(array_slice($choices, 0, 1, true), ChoicesHelper::only([$firstValue], $class::choices()));
        self::assertSame(array_slice($choices, 1, null, true), ChoicesHelper::except([$firstValue], $class::choices()));
        self::assertSame([], ChoicesHelper::only([], $class::choices()));
        self::assertSame($choices, ChoicesHelper::except([], $class::choices()));
    }

    public function testCaseIdentifierAndDisplayNameAreIndependent(): void
    {
        self::assertSame('VALUE_1', NativeIntEnum::VALUE_1->name);
        self::assertSame('NAME 1', NativeIntEnum::VALUE_1->name());
        self::assertSame('STRING NAME 1', NativeStringEnum::VALUE_1->name());
        self::assertSame('NAME 1', NativeIntEnum::VALUE_1->name());
    }

    public function testEmptyNamesAndBackingValuesArePreserved(): void
    {
        self::assertSame('', NativeEdgeCaseEnum::EMPTY_NAME->name());
        self::assertSame('', NativeEdgeCaseEnum::getName(NativeEdgeCaseEnum::EMPTY_NAME));
        self::assertSame('Empty backing value', NativeEdgeCaseEnum::getName(''));
        self::assertSame('Zero', NativeEdgeCaseEnum::getName('0'));
        self::assertSame(
            ['' => NativeEdgeCaseEnum::EMPTY_NAME, 'Empty backing value' => NativeEdgeCaseEnum::EMPTY_VALUE, 'Zero' => NativeEdgeCaseEnum::ZERO],
            NativeEdgeCaseEnum::choices(),
        );
        self::assertSame(
            ['name' => 'Zero', 'value' => '0'],
            NativeEdgeCaseEnum::arrays()[2],
        );
    }

    public static function dataProvider_testUnsupportedValuesDoNotHaveNames(): array
    {
        return [
            'null' => [null],
            'boolean' => [true],
            'float' => [1.5],
            'array' => [[1]],
            'object' => [new \stdClass()],
            'pure enum' => [\RoundingMode::HalfAwayFromZero],
            'foreign integer enum' => [ForeignIntEnum::VALUE_1],
            'foreign string enum' => [ForeignStringEnum::VALUE_1],
        ];
    }

    #[DataProvider('dataProvider_testUnsupportedValuesDoNotHaveNames')]
    public function testUnsupportedValuesDoNotHaveNames(mixed $value): void
    {
        self::assertNull(NativeIntEnum::getName($value, [1 => 'Override']));
        self::assertNull(NativeStringEnum::getName($value, ['one' => 'Override']));
    }

    public function testNameWorksWithoutAttributes(): void
    {
        self::assertSame('NAME 1', NativeEnumWithoutAttributes::VALUE_1->name());
        self::assertSame('NAME 2', NativeEnumWithoutAttributes::VALUE_2->name());
    }
}
