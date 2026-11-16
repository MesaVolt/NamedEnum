<?php

declare(strict_types=1);

namespace Mesavolt\Tests\Fixture;

use Mesavolt\Enum\Name;
use Mesavolt\Enum\NamedEnumInterface;
use Mesavolt\Enum\NamedEnumTrait;
use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum TranslatableNamedEnum: string implements NamedEnumInterface, TranslatableInterface
{
    use NamedEnumTrait;

    #[Name('translatable.one')]
    case VALUE_1 = 'one';

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans($this->name(), locale: $locale);
    }
}
