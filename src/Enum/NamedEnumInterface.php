<?php

declare(strict_types=1);

namespace Mesavolt\Enum;

interface NamedEnumInterface extends \BackedEnum
{
    public function name(): string;

    /** @return array<string, int|string> */
    public static function constants(): array;

    /** @return list<int|string> */
    public static function values(): array;

    /** @return array<int|string, string> */
    public static function getNames(?array $alternativeNames = null): array;

    public static function getName(mixed $value, ?array $alternativeNames = null): ?string;

    /** @return list<string> */
    public static function names(?array $alternativeNames = null): array;

    /** @return array<string, int|string> */
    public static function choices(?array $alternativeNames = null): array;

    /** @return list<array{name: string, value: int|string}> */
    public static function arrays(?array $alternativeNames = null): array;

    public static function isValid(mixed $value, bool $nullable = false, bool $strictCheck = true): bool;

    /** @throws \InvalidArgumentException */
    public static function ensureValid(mixed $value, bool $nullable = false, bool $strictCheck = true): void;
}
