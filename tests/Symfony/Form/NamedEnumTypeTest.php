<?php

declare(strict_types=1);

namespace Mesavolt\Tests\Symfony\Form;

use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use Mesavolt\Enum\NamedEnumInterface;
use Mesavolt\Symfony\Form\NamedEnumType;
use Mesavolt\Tests\Fixture\ForeignStringEnum;
use Mesavolt\Tests\Fixture\NamedEnumFormData;
use Mesavolt\Tests\Fixture\NativeEdgeCaseEnum;
use Mesavolt\Tests\Fixture\NativeEnumWithoutAttributes;
use Mesavolt\Tests\Fixture\NativeIntEnum;
use Mesavolt\Tests\Fixture\NativeStringEnum;
use Mesavolt\Tests\Fixture\TestEnum;
use Mesavolt\Tests\Fixture\TranslatableNamedEnum;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

#[AllowMockObjectsWithoutExpectations]
class NamedEnumTypeTest extends TypeTestCase
{
    public static function provideValidSubmissions(): array
    {
        return [
            'native string backing' => [NativeStringEnum::class, 'two', NativeStringEnum::VALUE_2],
            'native integer backing' => [NativeIntEnum::class, '2', NativeIntEnum::VALUE_2],
            'legacy string value' => [TestEnum::class, 'string', TestEnum::VALUE_STRING],
            'legacy integer value' => [TestEnum::class, '2', TestEnum::VALUE_2],
        ];
    }

    #[DataProvider('provideValidSubmissions')]
    public function testSubmitValidData(string $class, string $submitted, NamedEnumInterface|int|string $expected): void
    {
        $form = $this->factory->create(NamedEnumType::class, null, ['class' => $class]);

        $form->submit($submitted);

        self::assertTrue($form->isSynchronized());
        self::assertSame($expected, $form->getData());
        self::assertSame($submitted, $form->getViewData());
    }

    public function testSubmissionUpdatesTheModel(): void
    {
        $model = new NamedEnumFormData(NativeStringEnum::VALUE_1);
        $form = $this->factory->createBuilder(FormType::class, $model)
            ->add('status', NamedEnumType::class, ['class' => NativeStringEnum::class])
            ->getForm();

        $form->submit(['status' => 'two']);

        self::assertTrue($form->isSynchronized());
        self::assertTrue($form->get('status')->isSynchronized());
        self::assertEquals(new NamedEnumFormData(NativeStringEnum::VALUE_2), $model);
        self::assertSame($model, $form->getData());
    }

    public function testViewUsesNamedLabelsAndBackingValues(): void
    {
        $form = $this->factory->create(NamedEnumType::class, NativeStringEnum::VALUE_2, [
            'class' => NativeStringEnum::class,
        ]);

        $view = $form->createView();

        self::assertSame(['one', 'two', 'three'], array_column($view->vars['choices'], 'value'));
        self::assertSame(['STRING NAME 1', 'STRING NAME 2', 'UNNAMED'], array_column($view->vars['choices'], 'label'));
        self::assertSame(NativeStringEnum::cases(), array_column($view->vars['choices'], 'data'));
    }

    public function testLegacyViewUsesLabelsAndScalarData(): void
    {
        $view = $this->factory->create(NamedEnumType::class, TestEnum::VALUE_2, [
            'class' => TestEnum::class,
        ])->createView();

        self::assertSame('2', $view->vars['value']);
        self::assertSame(['NAME 1', 'NAME 2', 'NAME STRING'], array_column($view->vars['choices'], 'label'));
        self::assertSame(['1', '2', 'string'], array_column($view->vars['choices'], 'value'));
        self::assertSame([1, 2, 'string'], array_column($view->vars['choices'], 'data'));
    }

    public function testNamesCanBeProvidedWithoutAttributes(): void
    {
        $view = $this->factory->create(NamedEnumType::class, null, [
            'class' => NativeEnumWithoutAttributes::class,
        ])->createView();

        self::assertSame(['one', 'two'], array_column($view->vars['choices'], 'value'));
        self::assertSame(['NAME 1', 'NAME 2'], array_column($view->vars['choices'], 'label'));
        self::assertSame([NativeEnumWithoutAttributes::VALUE_1, NativeEnumWithoutAttributes::VALUE_2], array_column($view->vars['choices'], 'data'));
    }

    public function testAlternativeNames(): void
    {
        $form = $this->factory->create(NamedEnumType::class, null, [
            'class' => NativeStringEnum::class,
            'alternative_names' => ['three' => 'Alt 3', 'one' => 'Alt 1'],
        ]);

        $view = $form->createView();

        self::assertSame(['one', 'two', 'three'], array_column($view->vars['choices'], 'value'));
        self::assertSame(['Alt 1', 'STRING NAME 2', 'Alt 3'], array_column($view->vars['choices'], 'label'));
        self::assertSame([NativeStringEnum::VALUE_1, NativeStringEnum::VALUE_2, NativeStringEnum::UNNAMED], array_column($view->vars['choices'], 'data'));

        $form->submit('one');

        self::assertTrue($form->isSynchronized());
        self::assertSame(NativeStringEnum::VALUE_1, $form->getData());
    }

    public function testLegacyAlternativeNamesPreserveScalarData(): void
    {
        $form = $this->factory->create(NamedEnumType::class, null, [
            'class' => TestEnum::class,
            'alternative_names' => ['string' => 'String first', 1 => 'Integer last'],
        ]);

        $view = $form->createView();

        self::assertSame(['String first', 'Integer last'], array_column($view->vars['choices'], 'label'));
        self::assertSame(['string', '1'], array_column($view->vars['choices'], 'value'));

        $form->submit('string');

        self::assertTrue($form->isSynchronized());
        self::assertSame('string', $form->getData());
    }

    public function testEmptyAlternativeNamesUseDefaultNames(): void
    {
        $view = $this->factory->create(NamedEnumType::class, null, [
            'class' => NativeStringEnum::class,
            'alternative_names' => [],
        ])->createView();

        self::assertSame(['STRING NAME 1', 'STRING NAME 2', 'UNNAMED'], array_column($view->vars['choices'], 'label'));
    }

    public function testNumericAlternativeNamesRemainLabels(): void
    {
        $view = $this->factory->create(NamedEnumType::class, null, [
            'class' => NativeStringEnum::class,
            'alternative_names' => ['one' => '42'],
        ])->createView();

        self::assertSame('42', $view->vars['choices'][0]->label);
    }

    public function testExplicitChoiceLabelsTakePrecedence(): void
    {
        $view = $this->factory->create(NamedEnumType::class, null, [
            'class' => NativeStringEnum::class,
            'choices' => ['Explicit label' => NativeStringEnum::VALUE_2],
        ])->createView();

        self::assertSame('Explicit label', $view->vars['choices'][0]->label);
        self::assertSame(NativeStringEnum::VALUE_2, $view->vars['choices'][0]->data);
    }

    public function testTranslatableCasesAreTranslated(): void
    {
        $view = $this->factory->create(NamedEnumType::class, null, [
            'class' => TranslatableNamedEnum::class,
        ])->createView();

        self::assertSame('translatable.one', $view->vars['choices'][0]->label);
    }

    public function testAlternativeNamesOverrideTranslatableCases(): void
    {
        $view = $this->factory->create(NamedEnumType::class, null, [
            'class' => TranslatableNamedEnum::class,
            'alternative_names' => ['one' => 'Explicit translation'],
        ])->createView();

        self::assertSame('Explicit translation', $view->vars['choices'][0]->label);
    }

    public function testEmptySubmissionMapsToNull(): void
    {
        $form = $this->factory->create(NamedEnumType::class, NativeStringEnum::VALUE_1, [
            'class' => NativeStringEnum::class,
            'required' => false,
        ]);

        $form->submit('');

        self::assertTrue($form->isSynchronized());
        self::assertNull($form->getData());
    }

    public function testMultipleSubmissionMapsToEnumCases(): void
    {
        $form = $this->factory->create(NamedEnumType::class, null, [
            'class' => NativeStringEnum::class,
            'multiple' => true,
        ]);

        $form->submit(['one', 'two']);

        self::assertTrue($form->isSynchronized());
        self::assertSame([NativeStringEnum::VALUE_1, NativeStringEnum::VALUE_2], $form->getData());
    }

    public function testExpandedChoicesUseNamedLabels(): void
    {
        $view = $this->factory->create(NamedEnumType::class, null, [
            'class' => NativeStringEnum::class,
            'expanded' => true,
            'placeholder' => false,
        ])->createView();

        self::assertSame(['STRING NAME 1', 'STRING NAME 2', 'UNNAMED'], array_map(
            static fn(FormView $child): string => $child->vars['label'],
            array_values($view->children),
        ));
    }

    public function testNumericStringBackingValuesAreSubmittedAsStrings(): void
    {
        $form = $this->factory->create(NamedEnumType::class, null, [
            'class' => NativeEdgeCaseEnum::class,
        ]);

        $form->submit('0');

        self::assertTrue($form->isSynchronized());
        self::assertSame(NativeEdgeCaseEnum::ZERO, $form->getData());
    }

    public function testClassIsRequired(): void
    {
        $this->expectException(MissingOptionsException::class);

        $this->factory->create(NamedEnumType::class);
    }

    public static function provideInvalidClasses(): array
    {
        return [
            'unrelated class' => [\stdClass::class],
            'enum without names' => [ForeignStringEnum::class],
            'interface instead of enum' => [NamedEnumInterface::class],
            'nonexistent class' => ['UnknownEnumClass'],
        ];
    }

    #[DataProvider('provideInvalidClasses')]
    public function testRejectsUnsupportedClasses(string $class): void
    {
        $this->expectException(InvalidOptionsException::class);

        $this->factory->create(NamedEnumType::class, null, ['class' => $class]);
    }

    public function testAlternativeNamesMustBeAnArray(): void
    {
        $this->expectException(InvalidOptionsException::class);

        $this->factory->create(NamedEnumType::class, null, [
            'class' => NativeStringEnum::class,
            'alternative_names' => 'invalid',
        ]);
    }
}
