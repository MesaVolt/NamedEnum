<?php

declare(strict_types=1);

namespace Mesavolt\Tests\Doctrine;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use Mesavolt\Tests\Fixture\Doctrine\NativeEnumEntity;
use Mesavolt\Tests\Fixture\Doctrine\ScalarEnumEntity;
use Mesavolt\Tests\Fixture\NativeIntEnum;
use Mesavolt\Tests\Fixture\NativeStringEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class NativeNamedEnumPersistenceTest extends TestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $config = ORMSetup::createAttributeMetadataConfiguration([__DIR__ . '/../Fixture/Doctrine'], true);
        if (method_exists($config, 'enableNativeLazyObjects')) {
            $config->enableNativeLazyObjects(true);
        }

        $connection = DriverManager::getConnection(['driver' => 'pdo_sqlite', 'memory' => true]);

        $this->entityManager = new EntityManager($connection, $config);
    }

    protected function tearDown(): void
    {
        $this->entityManager->close();
        $this->entityManager->getConnection()->close();
    }

    private function createSchema(string $class = NativeEnumEntity::class): void
    {
        new SchemaTool($this->entityManager)->createSchema([$this->entityManager->getClassMetadata($class)]);
    }

    public function testExistingScalarRowsHydrateAsNamedEnumCases(): void
    {
        $this->createSchema(ScalarEnumEntity::class);
        $this->entityManager->getConnection()->insert('named_enum_entity', [
            'id' => 1,
            'integer_value' => 2,
            'string_value' => 'two',
            'nullable_value' => null,
        ]);

        $entity = $this->entityManager->find(NativeEnumEntity::class, 1);

        self::assertInstanceOf(NativeEnumEntity::class, $entity);
        self::assertSame(NativeIntEnum::VALUE_2, $entity->integerValue);
        self::assertSame('NAME 2', $entity->integerValue->name());
        self::assertSame(NativeStringEnum::VALUE_2, $entity->stringValue);
        self::assertSame('STRING NAME 2', $entity->stringValue->name());
        self::assertNull($entity->nullableValue);
    }

    public function testPersistingNamedEnumsKeepsBackingScalarsInDatabase(): void
    {
        $this->createSchema();
        $entity = new NativeEnumEntity();
        $entity->integerValue = NativeIntEnum::VALUE_2;
        $entity->stringValue = NativeStringEnum::VALUE_2;
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        $row = $this->entityManager->getConnection()->fetchAssociative('SELECT * FROM named_enum_entity');

        self::assertIsArray($row);
        self::assertSame(2, $row['integer_value']);
        self::assertSame('two', $row['string_value']);
        self::assertNull($row['nullable_value']);
    }

    public function testEnumsCanBeUsedAsQueryParameters(): void
    {
        $this->createSchema();
        $entity = new NativeEnumEntity();
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        $matches = $this->entityManager->getRepository(NativeEnumEntity::class)->findBy([
            'integerValue' => NativeIntEnum::VALUE_1,
            'stringValue' => NativeStringEnum::VALUE_1,
        ]);

        self::assertSame([$entity], $matches);
    }

    public function testEnumMappingDoesNotChangeTheDatabaseSchema(): void
    {
        $tool = new SchemaTool($this->entityManager);
        $scalar = $this->entityManager->getClassMetadata(ScalarEnumEntity::class);
        $native = $this->entityManager->getClassMetadata(NativeEnumEntity::class);

        self::assertSame($tool->getCreateSchemaSql([$scalar]), $tool->getCreateSchemaSql([$native]));
        $this->createSchema(ScalarEnumEntity::class);
        // Compare against the scalar baseline: older DBAL versions report unrelated SQLite differences.
        self::assertSame($tool->getUpdateSchemaSql([$scalar]), $tool->getUpdateSchemaSql([$native]));
    }

    public function testNullableEnumCasesRoundTrip(): void
    {
        $this->createSchema();
        $entity = new NativeEnumEntity();
        $entity->nullableValue = NativeIntEnum::VALUE_2;
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        $id = $entity->id;
        $this->entityManager->clear();

        $entity = $this->entityManager->find(NativeEnumEntity::class, $id);
        self::assertSame(NativeIntEnum::VALUE_2, $entity->nullableValue);
        $entity->nullableValue = null;
        $this->entityManager->flush();
        $this->entityManager->clear();

        self::assertNull($this->entityManager->find(NativeEnumEntity::class, $id)->nullableValue);
    }

    public static function provideInvalidStoredValues(): array
    {
        return [
            'integer backing' => [99, 'one'],
            'string backing' => [1, 'unknown'],
        ];
    }

    #[DataProvider('provideInvalidStoredValues')]
    public function testInvalidStoredValuesAreRejectedDuringHydration(int $integer, string $string): void
    {
        $this->createSchema();
        $this->entityManager->getConnection()->insert('named_enum_entity', [
            'id' => 1,
            'integer_value' => $integer,
            'string_value' => $string,
        ]);

        $this->expectException(MappingException::class);
        $this->entityManager->find(NativeEnumEntity::class, 1);
    }
}
