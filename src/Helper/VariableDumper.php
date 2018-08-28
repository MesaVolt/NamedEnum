<?php

namespace Mesavolt\Helper;

abstract class VariableDumper
{
    public static function dump($variable): string
    {
        switch ($type = \gettype($variable)) {
            case 'NULL':
                return 'null';
            case 'boolean':
            case 'integer':
            case 'double':
                return json_encode($variable);
            case 'string':
                return mb_substr($variable, 0, 100);
            case 'array':
            case 'resource':
                return $type;
            case 'object':
                return \get_class($variable);
            default:
                return 'unknown';
        }
    }
}
