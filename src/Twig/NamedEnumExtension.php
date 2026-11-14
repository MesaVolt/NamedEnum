<?php

namespace Mesavolt\Twig;


use Twig\Attribute\AsTwigFilter;
use Twig\Attribute\AsTwigFunction;

class NamedEnumExtension
{
    #[AsTwigFunction('enum_name'), AsTwigFilter('enum_name')]
    public static function enumName($value, $class, ?array $alternativeNames = null): ?string
    {
        return $class::getName($value, $alternativeNames);
    }

    #[AsTwigFunction('enum_values'), AsTwigFilter('enum_values')]
    public static function enumValues($class): array
    {
        return $class::values();
    }

    #[AsTwigFunction('enum_names'), AsTwigFilter('enum_names')]
    public static function enumNames($class, ?array $alternativeNames = null): array
    {
        return $class::names($alternativeNames);
    }

    #[AsTwigFunction('enum_constants'), AsTwigFilter('enum_constants')]
    public static function enumConstants($class): array
    {
        return $class::constants();
    }

    #[AsTwigFunction('enum_arrays'), AsTwigFilter('enum_arrays')]
    public static function enumArrays($class, ?array $alternativeNames = null): array
    {
        return $class::arrays($alternativeNames);
    }

    #[AsTwigFunction('enum_choices'), AsTwigFilter('enum_choices')]
    public static function enumChoices($class, ?array $alternativeNames = null): array
    {
        return $class::choices($alternativeNames);
    }
}
