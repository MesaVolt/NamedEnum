<?php

namespace Mesavolt\Tests\Rector\AddNamedEnumValueNamesTypeRector\Fixture;

use Mesavolt\Enum\NamedEnum;

abstract class Status extends NamedEnum
{
    private const NAMES = ['pending' => 'Pending'];

    protected static $VALUE_NAMES = self::NAMES;
}
-----
<?php

namespace Mesavolt\Tests\Rector\AddNamedEnumValueNamesTypeRector\Fixture;

use Mesavolt\Enum\NamedEnum;

abstract class Status extends NamedEnum
{
    private const NAMES = ['pending' => 'Pending'];

    protected static array $VALUE_NAMES = self::NAMES;
}
