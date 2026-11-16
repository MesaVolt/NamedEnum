<?php

namespace Mesavolt\Tests\Rector\AddNamedEnumValueNamesTypeRector\Fixture;

use Mesavolt\Enum\NamedEnum;

abstract class ApplicationEnum extends NamedEnum
{
    protected static $VALUE_NAMES = [];
}

abstract class Status extends ApplicationEnum
{
    protected static $VALUE_NAMES = [1 => 'First'];
}
-----
<?php

namespace Mesavolt\Tests\Rector\AddNamedEnumValueNamesTypeRector\Fixture;

use Mesavolt\Enum\NamedEnum;

abstract class ApplicationEnum extends NamedEnum
{
    protected static array $VALUE_NAMES = [];
}

abstract class Status extends ApplicationEnum
{
    protected static array $VALUE_NAMES = [1 => 'First'];
}
