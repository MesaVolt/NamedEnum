<?php

namespace Mesavolt\Tests\Rector\AddNamedEnumValueNamesTypeRector\Fixture;

use Mesavolt\Enum\StrictNamedEnum;

abstract class Status extends StrictNamedEnum
{
    protected static $VALUE_NAMES = ['first' => 'First'];
}
-----
<?php

namespace Mesavolt\Tests\Rector\AddNamedEnumValueNamesTypeRector\Fixture;

use Mesavolt\Enum\StrictNamedEnum;

abstract class Status extends StrictNamedEnum
{
    protected static array $VALUE_NAMES = ['first' => 'First'];
}
