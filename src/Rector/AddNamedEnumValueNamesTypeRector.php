<?php

declare(strict_types=1);

namespace Mesavolt\Rector;

use Mesavolt\Enum\NamedEnum;
use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\PropertyItem;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\CodeSample\CodeSample;
use Rector\RuleDoc\RuleDefinition;
use Rector\ValueObject\PhpVersion;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;

final class AddNamedEnumValueNamesTypeRector extends AbstractRector implements MinPhpVersionInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add the array type to VALUE_NAMES redeclarations in NamedEnum subclasses', [
            new CodeSample(
                <<<'PHP'
use Mesavolt\Enum\NamedEnum;

abstract class Status extends NamedEnum
{
    protected static $VALUE_NAMES = ['pending' => 'Pending'];
}
PHP,
                <<<'PHP'
use Mesavolt\Enum\NamedEnum;

abstract class Status extends NamedEnum
{
    protected static array $VALUE_NAMES = ['pending' => 'Pending'];
}
PHP,
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersion::PHP_74;
    }

    /** @param Class_ $node */
    public function refactor(Node $node): ?Node
    {
        if ($this->isName($node, NamedEnum::class) || !$this->isObjectType($node, new ObjectType(NamedEnum::class))) {
            return null;
        }

        $statements = [];
        $changed = false;

        foreach ($node->stmts as $statement) {
            if (!$statement instanceof Property || !$statement->isStatic()) {
                $statements[] = $statement;
                continue;
            }

            $valueNames = array_find(
                $statement->props,
                fn(PropertyItem $property): bool => $this->isName($property, 'VALUE_NAMES'),
            );

            if ($valueNames === null) {
                $statements[] = $statement;
                continue;
            }

            if ($statement->type instanceof Node) {
                if (!$statement->type instanceof Identifier || !$this->isName($statement->type, 'array')) {
                    throw new \LogicException(sprintf(
                        'Cannot migrate %s::$VALUE_NAMES: its declared type conflicts with the required array type.',
                        $this->getName($node),
                    ));
                }

                $statements[] = $statement;
                continue;
            }

            if ($valueNames->default !== null && !$this->getNativeType($valueNames->default)->isArray()->yes()) {
                throw new \LogicException(sprintf(
                    'Cannot migrate %s::$VALUE_NAMES: its initializer is not known to be an array.',
                    $this->getName($node),
                ));
            }

            // A declaration's type applies to every property in it; split it to leave siblings untyped.
            foreach ($statement->props as $index => $property) {
                $replacement = clone $statement;
                $replacement->props = [$property];
                if ($property === $valueNames) {
                    $replacement->type = new Identifier('array');
                }
                if ($index !== 0) {
                    $replacement->setAttribute('comments', []);
                }

                $statements[] = $replacement;
            }

            $changed = true;
        }

        if (!$changed) {
            return null;
        }

        $node->stmts = $statements;

        return $node;
    }
}
