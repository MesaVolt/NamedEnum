<?php

namespace Mesavolt\Enum;


abstract class ChoicesHelper
{
    public static function only(array $valuesToInclude, array $choices): array
    {
        if (count($valuesToInclude) === 0) {
            return [];
        }

        $filtered = [];
        foreach ($choices as $label => $value) {
            if (!in_array($value, $valuesToInclude, true)) {
                continue;
            }

            $filtered[$label] = $value;
        }

        return $filtered;
    }

    public static function except(array $valuesToExclude, array $choices): array
    {
        if (count($valuesToExclude) === 0) {
            return $choices;
        }

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
