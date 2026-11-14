<?php

namespace Mesavolt\Helper;

abstract class VariableDumper
{
    public static function dump($variable): string
    {
        return match ($type = \gettype($variable)) {
            'NULL', 'boolean', 'integer', 'double' => json_encode($variable),
            'string' => mb_substr($variable, 0, 100),
            'object' => $variable::class,
            default => $type,
        };
    }
}
