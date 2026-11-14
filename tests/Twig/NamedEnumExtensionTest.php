<?php

namespace Mesavolt\Tests\Twig;


use Mesavolt\Tests\Fixture\TestEnum;
use Mesavolt\Twig\NamedEnumExtension;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Extension\AttributeExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;
use Twig\TwigFunction;

class NamedEnumExtensionTest extends TestCase
{
    protected Environment $twig;

    protected const array UTILS = [
        'enum_arrays',
        'enum_choices',
        'enum_constants',
        'enum_name',
        'enum_names',
        'enum_values',
    ];

    protected function setUp(): void
    {
        $this->twig = new Environment(new FilesystemLoader(__DIR__));
        $this->twig->addExtension(new AttributeExtension(NamedEnumExtension::class));
    }

    public function testFilters(): void
    {
        $extension = $this->twig->getExtension(NamedEnumExtension::class);
        $filters = $extension->getFilters();

        $filterNames = array_map(static fn(TwigFilter $filter): string => $filter->getName(), $filters);
        self::assertArraysHaveEqualValuesIgnoringOrder(self::UTILS, $filterNames);
    }

    public function testFunctions(): void
    {
        $extension = $this->twig->getExtension(NamedEnumExtension::class);
        $functions = $extension->getFunctions();

        $filterNames = array_map(static fn(TwigFunction $function): string => $function->getName(), $functions);
        self::assertArraysHaveEqualValuesIgnoringOrder(self::UTILS, $filterNames);
    }

    public function testCanUseFilters(): void
    {
        $result = $this->twig->render('filters/test_arrays.html.twig', ['class' => TestEnum::class]);
        self::assertEquals('[NAME 1 => 1][NAME 2 => 2][NAME STRING => string]', $result);

        $result = $this->twig->render('filters/test_choices.html.twig', ['class' => TestEnum::class]);
        self::assertEquals('NAME 1:1|NAME 2:2|NAME STRING:string|', $result);

        $result = $this->twig->render('filters/test_constants.html.twig', ['class' => TestEnum::class]);
        self::assertEquals('VALUE_1:1|VALUE_2:2|VALUE_STRING:string|', $result);

        $result = $this->twig->render('filters/test_name.html.twig', ['enum' => TestEnum::VALUE_1]);
        self::assertEquals('NAME 1', $result);

        $result = $this->twig->render('filters/test_names.html.twig', ['class' => TestEnum::class]);
        self::assertEquals('NAME 1|NAME 2|NAME STRING|', $result);

        $result = $this->twig->render('filters/test_values.html.twig', ['class' => TestEnum::class]);
        self::assertEquals('1|2|string|', $result);
    }

    public function testCanUseFunctions(): void
    {
        $result = $this->twig->render('functions/test_arrays.html.twig', ['class' => TestEnum::class]);
        self::assertEquals('[NAME 1 => 1][NAME 2 => 2][NAME STRING => string]', $result);

        $result = $this->twig->render('functions/test_choices.html.twig', ['class' => TestEnum::class]);
        self::assertEquals('NAME 1:1|NAME 2:2|NAME STRING:string|', $result);

        $result = $this->twig->render('functions/test_constants.html.twig', ['class' => TestEnum::class]);
        self::assertEquals('VALUE_1:1|VALUE_2:2|VALUE_STRING:string|', $result);

        $result = $this->twig->render('functions/test_name.html.twig', ['enum' => TestEnum::VALUE_1]);
        self::assertEquals('NAME 1', $result);

        $result = $this->twig->render('functions/test_names.html.twig', ['class' => TestEnum::class]);
        self::assertEquals('NAME 1|NAME 2|NAME STRING|', $result);

        $result = $this->twig->render('functions/test_values.html.twig', ['class' => TestEnum::class]);
        self::assertEquals('1|2|string|', $result);
    }

}
