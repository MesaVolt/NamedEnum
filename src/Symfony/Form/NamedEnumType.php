<?php

namespace Mesavolt\Symfony\Form;

use Mesavolt\Enum\NamedEnum;
use Mesavolt\Enum\NamedEnumInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatableInterface;

class NamedEnumType extends AbstractType
{
    public function getParent(): ?string
    {
        return EnumType::class;
    }

    protected static function isLegacy(string $class): bool
    {
        return is_a($class, NamedEnum::class, true);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->define('alternative_names')
            ->allowedTypes('null', 'array')
            ->default(null);

        $resolver->setAllowedValues(
            'class',
            static fn(string $class): bool => (enum_exists($class) && is_a($class, NamedEnumInterface::class, true)) || self::isLegacy($class)
        );

        $resolver->setDefault(
            'choices',
            static fn(Options $options): array => $options['class']::choices($options['alternative_names']),
        );

        // Keep explicit choice labels, otherwise use alternative names or the case's display name.
        $resolver->setDefault('choice_label', static function (Options $options): \Closure {
            /** @var class-string<NamedEnumInterface|NamedEnum> $class */
            $class = $options['class'];
            $choices = $options['choices'];
            $alternativeNames = $options['alternative_names'];

            return static function (NamedEnumInterface|int|string $choice, int|string $key) use ($class, $alternativeNames): string|TranslatableInterface {
                // Key is a string, use it as the label
                if (is_string($key)) {
                    return $key;
                }

                if ($choice instanceof TranslatableInterface) {
                    return $choice;
                }

                return $class::getName($choice, $alternativeNames) ?? (string)$key;
            };
        });
    }
}
