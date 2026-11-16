<?php

declare(strict_types=1);

namespace Mesavolt\Tests\Fixture;

use Mesavolt\Enum\Name;
use Mesavolt\Enum\NamedEnum;

abstract class AttributeNamedEnum extends NamedEnum
{
    #[Name('Name 1')]
    public const VALUE_1 = 'VALUE 1';
    #[Name('Name 2')]
    public const VALUE_2 = 'VALUE 2';
    public const VALUE_3 = 'VALUE 3';
}
