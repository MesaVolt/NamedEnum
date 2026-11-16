<?php

declare(strict_types=1);

namespace Mesavolt\Enum;

#[\Attribute(\Attribute::TARGET_CLASS_CONSTANT)]
final readonly class Name
{
    public function __construct(public string $value)
    {
    }
}
