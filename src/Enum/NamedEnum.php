<?php

namespace Mesavolt\Enum;


abstract class NamedEnum
{
    protected static $VALUE_NAMES = [];

    public static function constants(): array
    {
        $class = static::class;
        $itemClass = new \ReflectionClass($class);

        return $itemClass->getConstants();
    }

    public static function getName($value): ?string
    {
        return static::$VALUE_NAMES[$value] ?? null;
    }

    public static function values(): array
    {
        return array_keys(static::$VALUE_NAMES);
    }

    public static function names(): array
    {
        return array_values(static::$VALUE_NAMES);
    }

    public static function choices(): array
    {
        return array_flip(static::$VALUE_NAMES);
    }
}
