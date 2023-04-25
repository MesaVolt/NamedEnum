<?php

namespace Mesavolt\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class NamedEnumExtension extends AbstractExtension
{
    public function getFilters(): array
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

    public function getFunctions(): array
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

    public function enumName($value, $class, array $alternativeNames = null): ?string
    {
        return $class::getName($value, $alternativeNames);
    }

    public function enumValues($class): array
    {
        return $class::values();
    }

    public function enumNames($class, array $alternativeNames = null): array
    {
        return $class::names($alternativeNames);
    }

    public function enumConstants($class): array
    {
        return $class::constants();
    }

    public function enumArrays($class, array $alternativeNames = null): array
    {
        return $class::arrays($alternativeNames);
    }

    public function enumChoices($class, array $alternativeNames = null): array
    {
        return $class::choices($alternativeNames);
    }
}
