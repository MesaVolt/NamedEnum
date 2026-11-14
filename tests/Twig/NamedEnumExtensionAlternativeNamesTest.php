<?php

declare(strict_types=1);

namespace Mesavolt\Tests\Twig;


use Mesavolt\Tests\Fixture\TestEnum;
use Mesavolt\Twig\NamedEnumExtension;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Extension\AttributeExtension;
use Twig\Loader\FilesystemLoader;

class NamedEnumExtensionAlternativeNamesTest extends TestCase
{
    protected Environment $twig;


    protected function setUp(): void
    {
        $this->twig = new Environment(new FilesystemLoader(__DIR__));
        $this->twig->addExtension(new AttributeExtension(NamedEnumExtension::class));
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
