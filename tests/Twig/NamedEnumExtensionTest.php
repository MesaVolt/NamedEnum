<?php

namespace Mesavolt\Tests\Twig;


use Mesavolt\Tests\Fixture\TestEnum;
use Mesavolt\Twig\NamedEnumExtension;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class NamedEnumExtensionTest extends TestCase
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
