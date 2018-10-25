<?php

namespace Mesavolt\Tests\Twig;


use Mesavolt\Tests\Fixture\TestEnum;
use Mesavolt\Twig\NamedEnumExtension;
use PHPUnit\Framework\TestCase;

class NamedEnumExtensionTest extends TestCase
{
    /** @var \Twig_Environment */
    protected $twig;
    /** @var NamedEnumExtension */
    protected $extension;

    protected function setUp()
    {
        $this->extension = new NamedEnumExtension();

        $this->twig = new \Twig_Environment(new \Twig_Loader_Filesystem(__DIR__));
        $this->twig->addExtension($this->extension);
    }

    protected function tearDown()/* The :void return type declaration that should be here would cause a BC issue */
    {
        $this->twig = null;
        $this->extension = null;
    }

    public function testFilters()
    {
        $extension = $this->twig->getExtension(NamedEnumExtension::class);
        $filters = $extension->getFilters();

        $this->assertCount(6, $filters);
        $this->assertEquals('enum_arrays', $filters[0]->getName());
        $this->assertEquals('enum_choices', $filters[1]->getName());
        $this->assertEquals('enum_constants', $filters[2]->getName());
        $this->assertEquals('enum_name', $filters[3]->getName());
        $this->assertEquals('enum_names', $filters[4]->getName());
        $this->assertEquals('enum_values', $filters[5]->getName());
    }

    public function testFunctions()
    {
        $functions = $this->extension->getFunctions();

        $this->assertCount(6, $functions);
        $this->assertEquals('enum_arrays', $functions[0]->getName());
        $this->assertEquals('enum_choices', $functions[1]->getName());
        $this->assertEquals('enum_constants', $functions[2]->getName());
        $this->assertEquals('enum_name', $functions[3]->getName());
        $this->assertEquals('enum_names', $functions[4]->getName());
        $this->assertEquals('enum_values', $functions[5]->getName());
    }

    public function testCanUseFilters()
    {
        $result = $this->twig->render('filters/test_arrays.html.twig', ['class' => TestEnum::class]);
        $this->assertEquals('[NAME 1 => 1][NAME 2 => 2][NAME STRING => string]', $result);

        $result = $this->twig->render('filters/test_choices.html.twig', ['class' => TestEnum::class]);
        $this->assertEquals('NAME 1:1|NAME 2:2|NAME STRING:string|', $result);

        $result = $this->twig->render('filters/test_constants.html.twig', ['class' => TestEnum::class]);
        $this->assertEquals('VALUE_1:1|VALUE_2:2|VALUE_STRING:string|', $result);

        $result = $this->twig->render('filters/test_name.html.twig', ['enum' => TestEnum::VALUE_1]);
        $this->assertEquals('NAME 1', $result);

        $result = $this->twig->render('filters/test_names.html.twig', ['class' => TestEnum::class]);
        $this->assertEquals('NAME 1|NAME 2|NAME STRING|', $result);

        $result = $this->twig->render('filters/test_values.html.twig', ['class' => TestEnum::class]);
        $this->assertEquals('1|2|string|', $result);
    }

    public function testCanUseFunctions()
    {
        $result = $this->twig->render('functions/test_arrays.html.twig', ['class' => TestEnum::class]);
        $this->assertEquals('[NAME 1 => 1][NAME 2 => 2][NAME STRING => string]', $result);

        $result = $this->twig->render('functions/test_choices.html.twig', ['class' => TestEnum::class]);
        $this->assertEquals('NAME 1:1|NAME 2:2|NAME STRING:string|', $result);

        $result = $this->twig->render('functions/test_constants.html.twig', ['class' => TestEnum::class]);
        $this->assertEquals('VALUE_1:1|VALUE_2:2|VALUE_STRING:string|', $result);

        $result = $this->twig->render('functions/test_name.html.twig', ['enum' => TestEnum::VALUE_1]);
        $this->assertEquals('NAME 1', $result);

        $result = $this->twig->render('functions/test_names.html.twig', ['class' => TestEnum::class]);
        $this->assertEquals('NAME 1|NAME 2|NAME STRING|', $result);

        $result = $this->twig->render('functions/test_values.html.twig', ['class' => TestEnum::class]);
        $this->assertEquals('1|2|string|', $result);
    }

}
