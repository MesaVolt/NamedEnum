<?php

declare(strict_types=1);

namespace Mesavolt\Enum;


abstract class ChoicesHelper
{
    protected static function guessAllChoices(array $valuesToInclude, string $method): array
    {
        $value = array_first($valuesToInclude);
        if (!is_a($value, NamedEnumInterface::class)) {
            throw new \InvalidArgumentException(sprintf('Values passed to %s are not named-enums', $method));
        }

        return $value::class::choices();
    }

    public static function only(array $valuesToInclude, ?array $choices = null): array
    {
        if (count($valuesToInclude) === 0) {
            return [];
        }

        $choices ??= self::guessAllChoices($valuesToInclude, __METHOD__);

        $filtered = [];
        foreach ($choices as $label => $value) {
            if (!in_array($value, $valuesToInclude, true)) {
                continue;
            }

            $filtered[$label] = $value;
        }

        return $filtered;
    }

    public static function except(array $valuesToExclude, ?array $choices = null): array
    {
        if (count($valuesToExclude) === 0) {
            return $choices;
        }

        $choices ??= self::guessAllChoices($valuesToExclude, __METHOD__);

        $filtered = [];
        foreach ($choices as $label => $value) {
            if (in_array($value, $valuesToExclude, true)) {
                continue;
            }

            $filtered[$label] = $value;
        }

        return $filtered;
    }
}
