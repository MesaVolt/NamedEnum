<?php

namespace Mesavolt\Tests\Rector\AddNamedEnumValueNamesTypeRector\Fixture;

use Mesavolt\Enum\NamedEnum;

abstract class Status extends NamedEnum
{
    // Preserve the sibling property's initializer and type.
    protected static $OTHER_NAMES = null, $VALUE_NAMES = ['pending' => 'Pending'];
}
-----
<?php

namespace Mesavolt\Tests\Rector\AddNamedEnumValueNamesTypeRector\Fixture;

use Mesavolt\Enum\NamedEnum;

abstract class Status extends NamedEnum
{
    // Preserve the sibling property's initializer and type.
    protected static $OTHER_NAMES = null;
    protected static array $VALUE_NAMES = ['pending' => 'Pending'];
}
