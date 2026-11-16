<?php

declare(strict_types=1);

namespace Mesavolt\Tests\Fixture;

use Mesavolt\Enum\Name;
use Mesavolt\Enum\NamedEnumInterface;
use Mesavolt\Enum\NamedEnumTrait;

enum NativeEdgeCaseEnum: string implements NamedEnumInterface
{
    use NamedEnumTrait;

    #[Name('')]
    case EMPTY_NAME = 'empty-name';

    #[Name('Empty backing value')]
    case EMPTY_VALUE = '';

    #[Name('Zero')]
    case ZERO = '0';
}
