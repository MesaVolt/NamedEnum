<?php

namespace Mesavolt\Twig;


use Mesavolt\Enum\NamedEnumInterface;
use Twig\Attribute\AsTwigFilter;
use Twig\Attribute\AsTwigFunction;

class NamedEnumExtension
{
    #[AsTwigFunction('enum_name'), AsTwigFilter('enum_name')]
    /** @var ?class-string<NamedEnumInterface|NamedEnum> $class */
    public static function enumName($value, ?string $class = null, ?array $alternativeNames = null): ?string
    {
        if ($class === null) {
            if (!$value instanceof NamedEnumInterface) {
                throw new \InvalidArgumentException('An enum class is required when the value is not a named enum case.');
            }

            $class = $value::class;
        }

        return $class::getName($value, $alternativeNames);
    }

    #[AsTwigFunction('enum_values'), AsTwigFilter('enum_values')]
    /** @var class-string<NamedEnumInterface|NamedEnum> $class */
    public static function enumValues(string $class): array
    {
        return $class::values();
    }

    #[AsTwigFunction('enum_names'), AsTwigFilter('enum_names')]
    /** @var class-string<NamedEnumInterface|NamedEnum> $class */
    public static function enumNames(string $class, ?array $alternativeNames = null): array
    {
        return $class::names($alternativeNames);
    }

    #[AsTwigFunction('enum_constants'), AsTwigFilter('enum_constants')]
    /** @var class-string<NamedEnumInterface|NamedEnum> $class */
    public static function enumConstants(string $class): array
    {
        return $class::constants();
    }

    #[AsTwigFunction('enum_arrays'), AsTwigFilter('enum_arrays')]
    /** @var class-string<NamedEnumInterface|NamedEnum> $class */
    public static function enumArrays(string $class, ?array $alternativeNames = null): array
    {
        return $class::arrays($alternativeNames);
    }

    #[AsTwigFunction('enum_choices'), AsTwigFilter('enum_choices')]
    /** @var class-string<NamedEnumInterface|NamedEnum> $class */
    public static function enumChoices(string $class, ?array $alternativeNames = null): array
    {
        return $class::choices($alternativeNames);
    }
}
