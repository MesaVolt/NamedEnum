<?php

declare(strict_types=1);

namespace Mesavolt\Tests\Fixture;

use Mesavolt\Enum\NamedEnumInterface;
use Mesavolt\Enum\NamedEnumTrait;

enum NativeEnumWithoutAttributes: string implements NamedEnumInterface
{
    use NamedEnumTrait;

    case VALUE_1 = 'one';
    case VALUE_2 = 'two';

    public function name(): string
    {
        return match ($this) {
            self::VALUE_1 => 'NAME 1',
            self::VALUE_2 => 'NAME 2',
        };
    }
}
