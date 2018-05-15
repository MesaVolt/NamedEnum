<?php

namespace Mesavolt\Enum;


abstract class StrictNamedEnum extends NamedEnum
{
    public static function getName($value): string
    {
        if (!array_key_exists($value, static::$VALUE_NAMES)) {
            $class = static::class;
            throw new \InvalidArgumentException("Undefined value \"$value\" for enum $class");
        }

        return static::$VALUE_NAMES[$value];
    }
}
