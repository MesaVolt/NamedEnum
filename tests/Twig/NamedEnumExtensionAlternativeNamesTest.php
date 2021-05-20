<?php

namespace Mesavolt\Tests\Twig;


use Mesavolt\Tests\Fixture\TestEnum;
use Mesavolt\Twig\NamedEnumExtension;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class NamedEnumExtensionAlternativeNamesTest extends TestCase
{
    /** @var Environment */
    protected $twig;
    /** @var NamedEnumExtension */
    protected $extension;

    protected function setUp(): void
    {
        $this->extension = new NamedEnumExtension();

        $this->twig = new Environment(new FilesystemLoader(__DIR__));
        $this->twig->addExtension($this->extension);
    }

    protected function tearDown(): void
    {
        $this->twig = null;
        $this->extension = null;
    }

    public function testFilters(): void
    {
        $extension = $this->twig->getExtension(NamedEnumExtension::class);
        $filters = $extension->getFilters();

        self::assertCount(6, $filters);
        self::assertEquals('enum_arrays', $filters[0]->getName());
        self::assertEquals('enum_choices', $filters[1]->getName());
        self::assertEquals('enum_constants', $filters[2]->getName());
        self::assertEquals('enum_name', $filters[3]->getName());
        self::assertEquals('enum_names', $filters[4]->getName());
        self::assertEquals('enum_values', $filters[5]->getName());
    }

    public function testFunctions(): void
    {
        $functions = $this->extension->getFunctions();

        self::assertCount(6, $functions);
        self::assertEquals('enum_arrays', $functions[0]->getName());
        self::assertEquals('enum_choices', $functions[1]->getName());
        self::assertEquals('enum_constants', $functions[2]->getName());
        self::assertEquals('enum_name', $functions[3]->getName());
        self::assertEquals('enum_names', $functions[4]->getName());
        self::assertEquals('enum_values', $functions[5]->getName());
    }

    public function testCanUseFilters(): void
    {
        $result = $this->twig->render('filters/test_arrays_alternative_names.html.twig', [
            'class' => TestEnum::class,
            'alternative_names' => TestEnum::$ALTERNATIVE_NAMES,
        ]);
        self::assertEquals('[ALTERNATIVE NAME 1 => 1][ALTERNATIVE NAME 2 => 2][ALTERNATIVE NAME STRING => string]', $result);

        $result = $this->twig->render('filters/test_choices_alternative_names.html.twig', [
            'class' => TestEnum::class,
            'alternative_names' => TestEnum::$ALTERNATIVE_NAMES,
        ]);
        self::assertEquals('ALTERNATIVE NAME 1:1|ALTERNATIVE NAME 2:2|ALTERNATIVE NAME STRING:string|', $result);

        $result = $this->twig->render('filters/test_name_alternative_names.html.twig', [
            'enum' => TestEnum::VALUE_1,
            'alternative_names' => TestEnum::$ALTERNATIVE_NAMES,
        ]);
        self::assertEquals('ALTERNATIVE NAME 1', $result);

        $result = $this->twig->render('filters/test_names_alternative_names.html.twig', [
            'class' => TestEnum::class,
            'alternative_names' => TestEnum::$ALTERNATIVE_NAMES,
        ]);
        self::assertEquals('ALTERNATIVE NAME 1|ALTERNATIVE NAME 2|ALTERNATIVE NAME STRING|', $result);
    }

    public function testCanUseFunctions(): void
    {
        $result = $this->twig->render('functions/test_arrays_alternative_names.html.twig', [
            'class' => TestEnum::class,
            'alternative_names' => TestEnum::$ALTERNATIVE_NAMES,
        ]);
        self::assertEquals('[ALTERNATIVE NAME 1 => 1][ALTERNATIVE NAME 2 => 2][ALTERNATIVE NAME STRING => string]', $result);

        $result = $this->twig->render('functions/test_choices_alternative_names.html.twig', [
            'class' => TestEnum::class,
            'alternative_names' => TestEnum::$ALTERNATIVE_NAMES,
        ]);
        self::assertEquals('ALTERNATIVE NAME 1:1|ALTERNATIVE NAME 2:2|ALTERNATIVE NAME STRING:string|', $result);

        $result = $this->twig->render('functions/test_name_alternative_names.html.twig', [
            'enum' => TestEnum::VALUE_1,
            'alternative_names' => TestEnum::$ALTERNATIVE_NAMES,
        ]);
        self::assertEquals('ALTERNATIVE NAME 1', $result);

        $result = $this->twig->render('functions/test_names_alternative_names.html.twig', [
            'class' => TestEnum::class,
            'alternative_names' => TestEnum::$ALTERNATIVE_NAMES,
        ]);
        self::assertEquals('ALTERNATIVE NAME 1|ALTERNATIVE NAME 2|ALTERNATIVE NAME STRING|', $result);

    }

}
