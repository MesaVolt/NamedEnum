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
            new TwigFilter('enum_name', [$this, 'enumName'])
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('enum_name', [$this, 'enumName'])
        ];
    }

    public function enumName($value, $class)
    {
        return $class::getName($value);
    }

}
