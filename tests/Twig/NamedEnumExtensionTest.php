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

        $this->assertCount(1, $filters);
        $this->assertEquals('enum_name', $filters[0]->getName());
    }

    public function testFunctions()
    {
        $functions = $this->extension->getFunctions();

        $this->assertCount(1, $functions);
        $this->assertEquals('enum_name', $functions[0]->getName());
    }

    public function testCanUseFilter()
    {
        $result = $this->twig->render('test_filter.html.twig', ['enum' => TestEnum::VALUE_1]);

        $this->assertEquals('NAME 1', trim($result));
    }

    public function testCanUseFunction()
    {
        $result = $this->twig->render('test_filter.html.twig', ['enum' => TestEnum::VALUE_2]);

        $this->assertEquals('NAME 2', trim($result));
    }

}
