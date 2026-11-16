<?php

declare(strict_types=1);

namespace Mesavolt\Tests\Fixture;

final class NamedEnumFormData
{
    public function __construct(public ?NativeStringEnum $status = null)
    {
    }
}
