<?php

namespace Mesavolt\Tests\Rector\AddNamedEnumValueNamesTypeRector\Fixture;

abstract class Status extends \Mesavolt\Enum\NamedEnum
{
    public static $VALUE_NAMES = [];
}
-----
<?php

namespace Mesavolt\Tests\Rector\AddNamedEnumValueNamesTypeRector\Fixture;

abstract class Status extends \Mesavolt\Enum\NamedEnum
{
    public static array $VALUE_NAMES = [];
}
