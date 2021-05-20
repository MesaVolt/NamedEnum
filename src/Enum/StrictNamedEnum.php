<?php

namespace Mesavolt\Enum;


abstract class StrictNamedEnum extends NamedEnum
{
    /**
     * Get the name of a value.
     * @throws \InvalidArgumentException if the value doesn't exist
     */
    public static function getName($value, array $alternativeNames = null): string
    {
        if (!array_key_exists($value, ($alternativeNames ?: static::$VALUE_NAMES))) {
            $class = static::class;
            throw new \InvalidArgumentException("Undefined value \"$value\" for enum $class");
        }

        return parent::getName($value, $alternativeNames);
    }
}
