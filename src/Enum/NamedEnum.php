<?php

namespace Mesavolt\Enum;


use Mesavolt\Helper\VariableDumper;

/**
 * Extend this class and implement a static $VALUE_NAMES property
 * to benefit from all the methods defined here.
 */
abstract class NamedEnum
{
    protected static array $VALUE_NAMES = [];

    /**
     * Get all the values, indexed by their constant name as defined in the class.
     */
    public static function constants(): array
    {
        $class = static::class;
        $itemClass = new \ReflectionClass($class);

        return $itemClass->getConstants();
    }


    private static function getNamesFromAttributes(): array
    {
        $names = [];
        $itemClass = new \ReflectionClass(static::class);
        foreach ($itemClass->getReflectionConstants(\ReflectionClassConstant::IS_PUBLIC) as $constant) {
            /** @var ?Name $nameAttribute */
            $nameAttribute = array_first($constant->getAttributes(Name::class))?->newInstance();
            $names[$constant->getValue()] = $nameAttribute?->value ?? $constant->getName();
        }

        return $names;
    }

    /**
     * Get all the names, indexed by their value.
     */
    public static function getNames(?array $alternativeNames = null): array
    {
        $valueNames = static::$VALUE_NAMES;
        $names = count($valueNames) === 0 ? self::getNamesFromAttributes() : $valueNames;
        return ($alternativeNames ?? []) + $names;
    }

    /**
     * Get the name of a value, or null if the value doesn't exist.
     */
    public static function getName($value, ?array $alternativeNames = null): ?string
    {
        return self::getNames($alternativeNames)[$value] ?? null;
    }

    /**
     * Get an array of all the values.
     */
    public static function values(): array
    {
        return array_values(self::constants());
    }

    /**
     * Get an array of all the names.
     */
    public static function names(?array $alternativeNames = null): array
    {
        return array_values(self::getNames($alternativeNames));
    }

    /**
     * Get an array of all the values indexed by name
     * (especially useful to use in a ChoiceType field in a Symfony Form).
     */
    public static function choices(?array $alternativeNames = null): array
    {
        $choices = [];
        foreach (self::getNames($alternativeNames) as $value => $name) {
            $choices[$name] = $value;
        }

        return $choices;
    }

    /**
     * Get all the values as an array of associative arrays of the form ['name' => NAME, 'value' => VALUE].
     */
    public static function arrays(?array $alternativeNames = null): array
    {
        $array = [];
        foreach(self::getNames($alternativeNames) as $value => $name) {
            $array[] = ['name' => $name, 'value' => $value];
        }

        return $array;
    }

    /**
     * Returns true if the specified value is declared as one of this enum values.
     */
    public static function isValid($value, bool $nullable = false, bool $strictCheck = true): bool
    {
        // Null and nullable is allowed
        if ($nullable && $value === null) {
            return true;
        }

        return \in_array($value, self::constants(), $strictCheck);
    }

    /**
     * Throws an exception if the specified value is not declared as one of this enum values.
     */
    public static function ensureValid($value, bool $nullable = false, bool $strictCheck = true): void
    {
        if (!self::isValid($value, $nullable, $strictCheck)) {
            $callerContext = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1];
            $callerMethod = array_key_exists('class', $callerContext)
                ? $callerContext['class'].$callerContext['type'].$callerContext['function']
                : $callerContext['file'].'::'.$callerContext['function'];

            throw new \InvalidArgumentException(
                sprintf(
                    'Invalid argument provided to %s - expected one of "%s", got "%s"',
                    $callerMethod,
                    implode(', ', self::constants()),
                    VariableDumper::dump($value)
                )
            );
        }
    }
}
