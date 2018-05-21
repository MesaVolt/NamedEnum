<?php

namespace Mesavolt\Enum;


/**
 * Extend this class and implement a static $VALUE_NAMES property
 * to benefit from all the methods defined here.
 */
abstract class NamedEnum
{
    protected static $VALUE_NAMES = [];

    /**
     * Get all the values, indexed by their constant name as defined in the class.
     */
    public static function constants(): array
    {
        $class = static::class;
        $itemClass = new \ReflectionClass($class);

        return $itemClass->getConstants();
    }

    /**
     * Get all the names, indexed by their value.
     */
    public static function getNames(): array
    {
        return static::$VALUE_NAMES;
    }

    /**
     * Get the name of a value, or null if the value doesn't exist.
     */
    public static function getName($value): ?string
    {
        return static::$VALUE_NAMES[$value] ?? null;
    }

    /**
     * Get an array of all the values.
     */
    public static function values(): array
    {
        return array_keys(static::$VALUE_NAMES);
    }

    /**
     * Get an array of all the names.
     */
    public static function names(): array
    {
        return array_values(static::$VALUE_NAMES);
    }

    /**
     * Get an array of all the values indexed by name
     * (especially useful tu use in a ChoiceType field in a Symfony Form).
     */
    public static function choices(): array
    {
        return array_flip(static::$VALUE_NAMES);
    }

    /**
     * Get all the values as an array of associative arrays of the form ['name' => NAME, 'value' => VALUE].
     */
    public static function arrays(): array
    {
        $array = [];
        foreach(static::$VALUE_NAMES as $value => $name) {
            $array[] = ['name' => $name, 'value' => $value];
        }

        return $array;
    }
}
