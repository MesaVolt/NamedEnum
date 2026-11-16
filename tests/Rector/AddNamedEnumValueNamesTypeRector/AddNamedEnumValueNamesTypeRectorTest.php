<?php

declare(strict_types=1);

namespace Mesavolt\Tests\Rector\AddNamedEnumValueNamesTypeRector;

use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class AddNamedEnumValueNamesTypeRectorTest extends AbstractRectorTestCase
{
    #[DataProvider('provideFixtures')]
    public function testMigration(string $fixture): void
    {
        $this->doTestFile($fixture);
    }

    public static function provideFixtures(): \Iterator
    {
        return self::yieldFilesFromDirectory(__DIR__ . '/Fixture');
    }

    #[DataProvider('provideInvalidFixtures')]
    public function testReportsUnsafeDeclarations(string $fixture, string $message): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessageIsOrContains($message);

        $this->doTestFile($fixture);
    }

    public static function provideInvalidFixtures(): array
    {
        return [
            'conflicting type' => [__DIR__ . '/InvalidFixture/conflicting_type.php.inc', 'its declared type conflicts'],
            'null initializer' => [__DIR__ . '/InvalidFixture/null_initializer.php.inc', 'its initializer is not known to be an array'],
            'scalar initializer' => [__DIR__ . '/InvalidFixture/scalar_initializer.php.inc', 'its initializer is not known to be an array'],
        ];
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
