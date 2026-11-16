<?php

declare(strict_types=1);

namespace Mesavolt\Tests\Fixture\Doctrine;

use Doctrine\ORM\Mapping as ORM;
use Mesavolt\Tests\Fixture\NativeIntEnum;
use Mesavolt\Tests\Fixture\NativeStringEnum;

#[ORM\Entity]
#[ORM\Table(name: 'named_enum_entity')]
class NativeEnumEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public ?int $id = null;

    #[ORM\Column(name: 'integer_value', type: 'integer', enumType: NativeIntEnum::class)]
    public NativeIntEnum $integerValue = NativeIntEnum::VALUE_1;

    #[ORM\Column(name: 'string_value', type: 'string', length: 32, enumType: NativeStringEnum::class)]
    public NativeStringEnum $stringValue = NativeStringEnum::VALUE_1;

    #[ORM\Column(name: 'nullable_value', type: 'integer', nullable: true, enumType: NativeIntEnum::class)]
    public ?NativeIntEnum $nullableValue = null;
}
