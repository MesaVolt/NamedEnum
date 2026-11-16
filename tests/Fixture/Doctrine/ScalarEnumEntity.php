<?php

declare(strict_types=1);

namespace Mesavolt\Tests\Fixture\Doctrine;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'named_enum_entity')]
class ScalarEnumEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public ?int $id = null;

    #[ORM\Column(name: 'integer_value', type: 'integer')]
    public int $integerValue = 1;

    #[ORM\Column(name: 'string_value', type: 'string', length: 32)]
    public string $stringValue = 'one';

    #[ORM\Column(name: 'nullable_value', type: 'integer', nullable: true)]
    public ?int $nullableValue = null;
}
