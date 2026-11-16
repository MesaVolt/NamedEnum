<?php

declare(strict_types=1);

namespace Mesavolt\Enum;

use Mesavolt\Helper\VariableDumper;

/**
 * @phpstan-require-implements NamedEnumInterface
 */
trait NamedEnumTrait
{
    public function name(): string
    {
        static $names = [];

        if (isset($names[$this->name])) {
            return $names[$this->name];
        }

        $attributes = new \ReflectionEnumUnitCase(self::class, $this->name)->getAttributes(Name::class);

        return $names[$this->name] = $attributes === []
            ? $this->name
            : $attributes[0]->newInstance()->value;
    }

    public static function constants(): array
    {
        return array_column(self::cases(), 'value', 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function getNames(?array $alternativeNames = null): array
    {
        $names = [];
        foreach (self::cases() as $case) {
            $names[$case->value] = $alternativeNames[$case->value] ?? $case->name();
        }

        return $names;
    }

    public static function getName(mixed $value, ?array $alternativeNames = null): ?string
    {
        if ($value instanceof self) {
            $value = $value->value;
        }

        if (!is_int($value) && !is_string($value)) {
            return null;
        }

        if (!array_key_exists($value, array_column(self::cases(), 'value', 'value'))) {
            return null;
        }

        return self::getNames($alternativeNames)[$value] ?? null;
    }

    public static function names(?array $alternativeNames = null): array
    {
        return array_values(self::getNames($alternativeNames));
    }

    /** @return array<string, static> */
    public static function choices(?array $alternativeNames = null): array
    {
        $choices = [];
        foreach (self::cases() as $case) {
            $choices[$alternativeNames[$case->value] ?? $case->name()] = $case;
        }

        return $choices;
    }

    public static function arrays(?array $alternativeNames = null): array
    {
        // PHP casts numeric string array keys to integers; retain the actual backing values.
        $values = array_column(self::cases(), 'value', 'value');
        $arrays = [];
        foreach (self::getNames($alternativeNames) as $value => $name) {
            $arrays[] = ['name' => $name, 'value' => $values[$value] ?? $value];
        }

        return $arrays;
    }

    public static function isValid(mixed $value, bool $nullable = false, bool $strictCheck = true): bool
    {
        if (is_object($value)) {
            return $value instanceof self;
        }

        if ($nullable && $value === null) {
            return true;
        }

        return in_array($value, self::values(), $strictCheck);
    }

    public static function ensureValid(mixed $value, bool $nullable = false, bool $strictCheck = true): void
    {
        if (self::isValid($value, $nullable, $strictCheck)) {
            return;
        }

        $callerContext = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1] ?? [];
        $callerMethod = isset($callerContext['class'])
            ? $callerContext['class'] . $callerContext['type'] . $callerContext['function']
            : ($callerContext['file'] ?? self::class) . '::' . ($callerContext['function'] ?? __FUNCTION__);

        throw new \InvalidArgumentException(sprintf(
            'Invalid argument provided to %s - expected one of "%s", got "%s"',
            $callerMethod,
            implode(', ', self::values()),
            VariableDumper::dump($value),
        ));
    }
}
