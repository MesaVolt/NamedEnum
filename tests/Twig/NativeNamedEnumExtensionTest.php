<?php

declare(strict_types=1);

namespace Mesavolt\Tests\Twig;

use Mesavolt\Tests\Fixture\ForeignStringEnum;
use Mesavolt\Tests\Fixture\NativeStringEnum;
use Mesavolt\Twig\NamedEnumExtension;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Extension\AttributeExtension;
use Twig\Loader\FilesystemLoader;

class NativeNamedEnumExtensionTest extends TestCase
{
    private Environment $twig;

    protected function setUp(): void
    {
        $this->twig = new Environment(new FilesystemLoader(__DIR__ . '/native'), ['strict_variables' => true]);
        $this->twig->addExtension(new AttributeExtension(NamedEnumExtension::class));
    }

    public function testCanUseFilters(): void
    {
        $context = [
            'class' => NativeStringEnum::class,
            'value' => NativeStringEnum::VALUE_1->value,
            'alternative_names' => null,
        ];

        $result = $this->twig->render('filters/test_arrays.twig', $context);
        self::assertEquals('[STRING NAME 1 => one][STRING NAME 2 => two][UNNAMED => three]', $result);

        $result = $this->twig->render('filters/test_choices.twig', $context);
        self::assertEquals('STRING NAME 1:one|STRING NAME 2:two|UNNAMED:three|', $result);

        $result = $this->twig->render('filters/test_constants.twig', $context);
        self::assertEquals('VALUE_1:one|VALUE_2:two|UNNAMED:three|', $result);

        $result = $this->twig->render('filters/test_name.twig', $context);
        self::assertEquals('STRING NAME 1', $result);

        $result = $this->twig->render('filters/test_names.twig', $context);
        self::assertEquals('STRING NAME 1|STRING NAME 2|UNNAMED|', $result);

        $result = $this->twig->render('filters/test_values.twig', $context);
        self::assertEquals('one|two|three|', $result);
    }

    public function testCanUseFunctions(): void
    {
        $context = [
            'class' => NativeStringEnum::class,
            'value' => NativeStringEnum::VALUE_1->value,
            'alternative_names' => null,
        ];

        $result = $this->twig->render('functions/test_arrays.twig', $context);
        self::assertEquals('[STRING NAME 1 => one][STRING NAME 2 => two][UNNAMED => three]', $result);

        $result = $this->twig->render('functions/test_choices.twig', $context);
        self::assertEquals('STRING NAME 1:one|STRING NAME 2:two|UNNAMED:three|', $result);

        $result = $this->twig->render('functions/test_constants.twig', $context);
        self::assertEquals('VALUE_1:one|VALUE_2:two|UNNAMED:three|', $result);

        $result = $this->twig->render('functions/test_name.twig', $context);
        self::assertEquals('STRING NAME 1', $result);

        $result = $this->twig->render('functions/test_names.twig', $context);
        self::assertEquals('STRING NAME 1|STRING NAME 2|UNNAMED|', $result);

        $result = $this->twig->render('functions/test_values.twig', $context);
        self::assertEquals('one|two|three|', $result);
    }

    public function testCanUseFiltersWithAlternativeNames(): void
    {
        $context = [
            'class' => NativeStringEnum::class,
            'value' => NativeStringEnum::VALUE_1,
            'alternative_names' => [NativeStringEnum::VALUE_1->value => 'Alternative name'],
        ];

        $result = $this->twig->render('filters/test_arrays.twig', $context);
        self::assertEquals('[Alternative name => one][STRING NAME 2 => two][UNNAMED => three]', $result);

        $result = $this->twig->render('filters/test_choices.twig', $context);
        self::assertEquals('Alternative name:one|STRING NAME 2:two|UNNAMED:three|', $result);

        $result = $this->twig->render('filters/test_constants.twig', $context);
        self::assertEquals('VALUE_1:one|VALUE_2:two|UNNAMED:three|', $result);

        $result = $this->twig->render('filters/test_name.twig', $context);
        self::assertEquals('Alternative name', $result);

        $result = $this->twig->render('filters/test_names.twig', $context);
        self::assertEquals('Alternative name|STRING NAME 2|UNNAMED|', $result);

        $result = $this->twig->render('filters/test_values.twig', $context);
        self::assertEquals('one|two|three|', $result);
    }

    public function testCanUseFunctionsWithAlternativeNames(): void
    {
        $context = [
            'class' => NativeStringEnum::class,
            'value' => NativeStringEnum::VALUE_1,
            'alternative_names' => [NativeStringEnum::VALUE_1->value => 'Alternative name'],
        ];

        $result = $this->twig->render('functions/test_arrays.twig', $context);
        self::assertEquals('[Alternative name => one][STRING NAME 2 => two][UNNAMED => three]', $result);

        $result = $this->twig->render('functions/test_choices.twig', $context);
        self::assertEquals('Alternative name:one|STRING NAME 2:two|UNNAMED:three|', $result);

        $result = $this->twig->render('functions/test_constants.twig', $context);
        self::assertEquals('VALUE_1:one|VALUE_2:two|UNNAMED:three|', $result);

        $result = $this->twig->render('functions/test_name.twig', $context);
        self::assertEquals('Alternative name', $result);

        $result = $this->twig->render('functions/test_names.twig', $context);
        self::assertEquals('Alternative name|STRING NAME 2|UNNAMED|', $result);

        $result = $this->twig->render('functions/test_values.twig', $context);
        self::assertEquals('one|two|three|', $result);
    }

    public function testCasesCanIdentifyTheirOwnEnumInFilters(): void
    {
        $result = $this->twig->render('filters/test_case.twig', ['value' => NativeStringEnum::VALUE_1]);
        self::assertEquals('STRING NAME 1', $result);

        $result = $this->twig->render('filters/test_case_alternative.twig', [
            'value' => NativeStringEnum::VALUE_1,
            'alternative_names' => [NativeStringEnum::VALUE_1->value => 'Alternative name'],
        ]);
        self::assertEquals('Alternative name', $result);
    }

    public function testCasesCanIdentifyTheirOwnEnumInFunctions(): void
    {
        $result = $this->twig->render('functions/test_case.twig', ['value' => NativeStringEnum::VALUE_1]);
        self::assertEquals('STRING NAME 1', $result);

        $result = $this->twig->render('functions/test_case_alternative.twig', [
            'value' => NativeStringEnum::VALUE_1,
            'alternative_names' => [NativeStringEnum::VALUE_1->value => 'Alternative name'],
        ]);
        self::assertEquals('Alternative name', $result);
    }

    public function testExplicitClassStillRejectsForeignCases(): void
    {
        $result = $this->twig->render('functions/test_name.twig', [
            'class' => NativeStringEnum::class,
            'value' => ForeignStringEnum::VALUE_1,
            'alternative_names' => null,
        ]);
        self::assertEquals('', $result);
    }

    public function testScalarValuesRequireAnExplicitClass(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageIsOrContains('An enum class is required');

        NamedEnumExtension::enumName('one');
    }
}
