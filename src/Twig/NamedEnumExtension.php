<?php

namespace Mesavolt\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class NamedEnumExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('enum_arrays', [$this, 'enumArrays']),
            new TwigFilter('enum_choices', [$this, 'enumChoices']),
            new TwigFilter('enum_constants', [$this, 'enumConstants']),
            new TwigFilter('enum_name', [$this, 'enumName']),
            new TwigFilter('enum_names', [$this, 'enumNames']),
            new TwigFilter('enum_values', [$this, 'enumValues']),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('enum_arrays', [$this, 'enumArrays']),
            new TwigFunction('enum_choices', [$this, 'enumChoices']),
            new TwigFunction('enum_constants', [$this, 'enumConstants']),
            new TwigFunction('enum_name', [$this, 'enumName']),
            new TwigFunction('enum_names', [$this, 'enumNames']),
            new TwigFunction('enum_values', [$this, 'enumValues']),
        ];
    }

    public function enumName($value, $class)
    {
        return $class::getName($value);
    }

    public function enumValues($class)
    {
        return $class::values();
    }

    public function enumNames($class)
    {
        return $class::names();
    }

    public function enumConstants($class)
    {
        return $class::constants();
    }

    public function enumArrays($class)
    {
        return $class::arrays();
    }

    public function enumChoices($class)
    {
        return $class::choices();
    }
}
