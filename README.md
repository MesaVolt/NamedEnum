# Named enum for PHP 8.5+

[![Latest Stable Version](https://poser.pugx.org/mesavolt/named-enum/v/stable)](https://packagist.org/packages/mesavolt/named-enum)
[![License](https://poser.pugx.org/mesavolt/named-enum/license)](https://packagist.org/packages/mesavolt/named-enum)

## Installation

Requires PHP 8.5 or newer.

```bash
composer require mesavolt/named-enum
```

## Usage

### Using native enums

Define an enum that implements `NamedEnumInterface`. Use `NamedEnumTrait` for the default implementation since
[enums aren't extendable](https://www.php.net/manual/en/language.enumerations.object-differences.inheritance.php)
and implement the `NamedEnumInterface::name(): string` method to provide the names:

```php
<?php

use Mesavolt\Enum\NamedEnumInterface;
use Mesavolt\Enum\NamedEnumTrait;

enum ArticleStatus: string implements NamedEnumInterface
{
    use NamedEnumTrait;

    case Pending = 'pending';
    case Published = 'published';
    
    public function name(): string {
        return match($this) {
            self::Pending => 'Awaiting review',
            self::Published => 'Published',
        };
    }
}

ArticleStatus::Pending->name(); // 'Awaiting review'
ArticleStatus::Published->name(); // 'Published'
```

Alternatively, you can add `#[Name]` attributes to the enum cases. The `NamedEnumTrait::name()` method uses reflexion to
extract the names from the attributes and falls-back to using the identifier for cases with no `#[Name]` attribute.

```php
<?php

use Mesavolt\Enum\Name;
use Mesavolt\Enum\NamedEnumInterface;
use Mesavolt\Enum\NamedEnumTrait;

enum ArticleStatus: string implements NamedEnumInterface
{
    use NamedEnumTrait;

    #[Name('Awaiting review')]
    case Pending = 'pending';

    case Published = 'published';
}

ArticleStatus::Pending->name(); // 'Awaiting review'
ArticleStatus::Published->name(); // 'Published'
```

You can then use the `NamedEnumInterface::name()` and `NamedEnumInterface::getName()` methods to retrieve the
names from an enum case or a matching scalar value. Both integer-backed and string-backed enums are supported.

```php
$article = new Article();
$article->status = ArticleStatus::Pending;

$article->status->name;   // 'Pending': PHP's case identifier, inherited from native PHP enums
$article->status->name(); // 'Awaiting review': the display name configured in the #[Name] attribute

$article->status->name();                 // 'Awaiting review'
ArticleStatus::getName($article->status); // 'Awaiting review'
ArticleStatus::getName('pending');        // 'Awaiting review'
ArticleStatus::getName('unknown');        // null

$alternativeNames = [ArticleStatus::Pending->value => 'Not published', ArticleStatus::Published->value => 'Published'];
ArticleStatus::getName($article->status, $alternativeNames); // 'Not published'
```

Other methods helper methods exist on `NamedEnumInterface`:

```php
ArticleStatus::constants(); // ['Pending' => 'pending', 'Published' => 'published']
ArticleStatus::values();    // ['pending', 'published']
ArticleStatus::getNames();  // ['pending' => 'Awaiting review', 'published' => 'Published']
ArticleStatus::names();     // ['Awaiting review', 'Published']
ArticleStatus::choices();   // ['Awaiting review' => ArticleStatus::Pending, 'Published' => ArticleStatus::Published]
ArticleStatus::arrays();    // [
                            //     ['name' => 'Awaiting review', 'value' => 'pending'],
                            //     ['name' => 'Published', 'value' => 'published'],
                            // ]
```

`NamedEnumInterface::constants()` includes enum cases, not constants and case aliases.
The name `constants()` is kept from the legacy constant-based `NamedEnum` class to facilitate migration from v0 to v1,
and to avoid conflict with the native `Enum::cases()` method.

### Using legacy constant-based classes

You can also extend the legacy `NamedEnum` and `StrictNamedEnum` classes and define the names in the `$VALUE_NAMES`
static class variable.

```php
use Mesavolt\Enum\NamedEnum;

abstract class MyEnum extends NamedEnum
{
    public const FOO = 'foo';
    public const BAR = 'bar';

    protected static array $VALUE_NAMES = [
        self::FOO => 'Foo name',
        self::BAR => 'Bar name',
    ];
}

MyEnum::getName(MyEnum::BAR); // 'Bar name'
```

> [!TIP]
> In legacy constant-based classes, `NamedEnum::$VALUE_NAMES` is strongly typed as an array since v1,
> but that wasn't the case in v0.
>
> This package provides a custom `rector` rule to bulk-add the missing type to your existing `NamedEnum` subclasses.
> ```php
> return RectorConfig::configure()
>     // your rector config statements
>     // [...]
>     // add this 👇
>     ->withRules([
>         \Mesavolt\Rector\AddNamedEnumValueNamesTypeRector::class,
>     ]);
> ```

You can also migrate `NamedEnum` subclasses to native enums. When replacing a scalar constant with an enum case,
use `MyEnum::MyCase->value` everywhere in your code that still expects a scalar value. You should also update property
and parameter types, comparisons, and persistence mapping together.

Subclasses that mix integer/string constants or use class inheritance need a different native design,
since those are not possible with the native enums as of PHP 8.5.

### Validation

Use the native `from()` and `tryFrom()` methods to convert external scalar values into enum cases:

```php
$status = ArticleStatus::from('pending');    // ArticleStatus::Pending
$status = ArticleStatus::from('unknown');    // throws \ValueError
$status = ArticleStatus::tryFrom('unknown'); // null
```

The `NamedEnumInterface::isValid($value)` and `NamedEnumInterface::ensureValid()` methods accept both backing values and
enum cases:

```php
ArticleStatus::isValid('pending');              // true
ArticleStatus::isValid(ArticleStatus::Pending); // true
ArticleStatus::isValid(null);                   // false
ArticleStatus::isValid(null, nullable: true);   // true

ArticleStatus::ensureValid('pending'); // Returns normally
ArticleStatus::ensureValid('unknown'); // Throws \InvalidArgumentException
```

## Twig integration

This package provides the following set of helpers for Twig:

- `enum_name(value, ?class)`
- `enum_names(class)`
- `enum_values(class)`
- `enum_constants(class)`
- `enum_choices(class)`
- `enum_arrays(class)`.

They are all available as functions and filters and support both native enum cases and legacy constant-based enums.

```twig
{% set status = enum('ArticleStatus').Pending %}
{{ status|enum_name }}
{{ enum_name(status) }}
{{ status|enum_name(alternativeNames: alternative_names) }}
```

You must explicitly pass the classname to `enum_name` for values from legacy constant-based classes:

```twig
{{ 'foo'|enum_name('MyEnum') }}
{{ enum_name('foo', 'MyEnum') }}
{{ enum_name('foo', 'MyEnum', alternative_names) }}
```

You can also mix-match this package's filters and functions with Twig's native `enum` and `enum_cases` functions:

```twig
{# using Twig's native enum function #}
{{ enum('ArticleStatus').Pending|enum_name }}

{# using Twig's native enum_cases function #}
{% for case in enum_cases('ArticleStatus') %}
    {{ case|enum_name }}: {{ case.value }}
{% endfor %}
```

If you use the default
[auto-configuring feature of Symfony](https://symfony.com/doc/current/service_container/3.3-di-changes.html),
you only need to register the `Mesavolt\Twig\NamedEnumExtension` class as a service in your `services.yaml` file.

If you don't use the auto-configuring feature or if it's not available in your version, you need to explicitly apply the
necessary tags when you register the extension as a service.

```yaml
# config/services.yaml
services:

    # Use this if you use the default auto-configuring feature of Symfony >=3.3  DI container
    Mesavolt\Twig\NamedEnumExtension: ~

    # Use this if you **don't** use the auto-configuring feature of Symfony >=3.3 DI container
    app.named_enum_extension:
        class: Mesavolt\Twig\NamedEnumExtension
        tags: { name: twig.atribute_extension }
```

## Doctrine

Doctrine supports backed enums through the `enumType` mapping option.

```php
use App\ArticleStatus;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Article 
{
    #[ORM\Column(type: 'string', enumType: ArticleStatus::class)]
    public ArticleStatus $status = ArticleStatus::Pending;
}
```

## Symfony forms

This package provides the `NamedEnumType` field type to allow the users to choose one or more options defined in a
named enum. It extends Symfony's `EnumType` and works with both native enums and legacy constant-based classes.

```php
use Mesavolt\Symfony\Form\NamedEnumType;

$builder->add('status', NamedEnumType::class, [
    'class' => ArticleStatus::class,
    // you can pass alternative names if necessary
    // 'alternative_names' => [
    //     ArticleStatus::Pending->value => 'Not published',
    //     ArticleStatus::Published->value => 'Published',
    // ],
]);
```

If you need to only expose some values, you can use `ChoicesHelper::only()` and `ChoicesHelper::except()` to filter
the choices:

```php
use Mesavolt\Enum\ChoicesHelper;

$choices = ChoicesHelper::only([ArticleStatus::Pending]);
$choices = ChoicesHelper::except([ArticleStatus::Pending]);

// you must explicitly specify the complete choices list for legacy constant-bases classes
$choices = ChoicesHelper::only([MyEnum::FOO], MyEnum::choices());
$choices = ChoicesHelper::except([MyEnum::BAR], MyEnum::choices());
```

## Development

```bash
composer install
composer dump-autoload # make sure vendor/autoload.php exists
composer test
```
