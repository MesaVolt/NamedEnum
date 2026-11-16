<?php

namespace Mesavolt\Tests\Rector\AddNamedEnumValueNamesTypeRector\Fixture;

use Mesavolt\Enum\NamedEnum;

abstract class Status extends NamedEnum
{
    public const PENDING = 'pending';

    /** Display names for the status values. */
    #[\Override]
    protected static $VALUE_NAMES = [
        self::PENDING => 'Pending', // Preserve this comment.
    ];
}
-----
<?php

namespace Mesavolt\Tests\Rector\AddNamedEnumValueNamesTypeRector\Fixture;

use Mesavolt\Enum\NamedEnum;

abstract class Status extends NamedEnum
{
    public const PENDING = 'pending';

    /** Display names for the status values. */
    #[\Override]
    protected static array $VALUE_NAMES = [
        self::PENDING => 'Pending', // Preserve this comment.
    ];
}
