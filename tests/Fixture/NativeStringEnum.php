<?php

declare(strict_types=1);

namespace Mesavolt\Tests\Fixture;

use Mesavolt\Enum\Name;
use Mesavolt\Enum\NamedEnumInterface;
use Mesavolt\Enum\NamedEnumTrait;

enum NativeStringEnum: string implements NamedEnumInterface
{
    use NamedEnumTrait;

    #[Name('STRING NAME 1')]
    case VALUE_1 = 'one';

    #[Name('STRING NAME 2')]
    case VALUE_2 = 'two';

    case UNNAMED = 'three';
}
