<?php

declare(strict_types=1);

namespace Mesavolt\Tests\Fixture;

use Mesavolt\Enum\Name;
use Mesavolt\Enum\NamedEnumInterface;
use Mesavolt\Enum\NamedEnumTrait;

enum NativeIntEnum: int implements NamedEnumInterface
{
    use NamedEnumTrait;

    #[Name('NAME 1')]
    case VALUE_1 = 1;

    #[Name('NAME 2')]
    case VALUE_2 = 2;

    case UNNAMED = 3;

    public const self ALIAS = self::VALUE_1;
    public const string METADATA = 'Not a case';
}
