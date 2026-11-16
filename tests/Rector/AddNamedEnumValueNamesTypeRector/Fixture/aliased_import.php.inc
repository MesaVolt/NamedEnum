<?php

namespace Mesavolt\Tests\Rector\AddNamedEnumValueNamesTypeRector\Fixture;

use Mesavolt\Enum\NamedEnum as LegacyEnum;

abstract class Status extends LegacyEnum
{
    protected static $VALUE_NAMES = [];
}
-----
<?php

namespace Mesavolt\Tests\Rector\AddNamedEnumValueNamesTypeRector\Fixture;

use Mesavolt\Enum\NamedEnum as LegacyEnum;

abstract class Status extends LegacyEnum
{
    protected static array $VALUE_NAMES = [];
}
