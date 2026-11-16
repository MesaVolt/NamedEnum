<?php

declare(strict_types=1);

namespace Mesavolt\Tests\Enum;


use Mesavolt\Enum\ChoicesHelper;
use Mesavolt\Tests\Fixture\NativeStringEnum;
use Mesavolt\Tests\Fixture\TestEnum;
use PHPUnit\Framework\TestCase;

class ChoicesHelperTest extends TestCase
{
    public function testExcept(): void
    {
        $choices = TestEnum::choices();
        $only = ChoicesHelper::except([TestEnum::VALUE_2], $choices);

        self::assertCount(2, $only);

        self::assertContains(TestEnum::VALUE_1, $only);
        self::assertContains(TestEnum::VALUE_STRING, $only);

        self::assertArrayHasKey(TestEnum::getName(TestEnum::VALUE_1), $only);
        self::assertArrayHasKey(TestEnum::getName(TestEnum::VALUE_STRING), $only);

        $only = ChoicesHelper::except([], $choices);
        self::assertEquals($choices, $only);
    }

    public function testOnly(): void
    {
        $choices = TestEnum::choices();
        $only = ChoicesHelper::only([TestEnum::VALUE_1, TestEnum::VALUE_STRING], $choices);

        self::assertCount(2, $only);

        self::assertContains(TestEnum::VALUE_1, $only);
        self::assertContains(TestEnum::VALUE_STRING, $only);

        self::assertArrayHasKey(TestEnum::getName(TestEnum::VALUE_1), $only);
        self::assertArrayHasKey(TestEnum::getName(TestEnum::VALUE_STRING), $only);

        $only = ChoicesHelper::only([], $choices);
        self::assertEquals([], $only);
    }

    public function testOnlyCanGuessChoicesListForNamedEnumInterface(): void
    {
        $only = ChoicesHelper::only([NativeStringEnum::VALUE_1]);
        self::assertSame(['STRING NAME 1' => NativeStringEnum::VALUE_1], $only);
    }

    public function testExceptCanGuessChoicesListForNamedEnumInterface(): void
    {
        $only = ChoicesHelper::except([NativeStringEnum::UNNAMED]);
        self::assertSame(['STRING NAME 1' => NativeStringEnum::VALUE_1, 'STRING NAME 2' => NativeStringEnum::VALUE_2], $only);
    }
}
