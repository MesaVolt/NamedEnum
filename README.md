# Named enum for PHP 7.1 +

[![Latest Stable Version](https://poser.pugx.org/mesavolt/named-enum/v/stable)](https://packagist.org/packages/mesavolt/named-enum)
[![Build Status](https://travis-ci.org/MesaVolt/NamedEnum.svg)](https://travis-ci.org/MesaVolt/NamedEnum)
[![Coverage Status](https://coveralls.io/repos/github/MesaVolt/NamedEnum/badge.svg)](https://coveralls.io/github/MesaVolt/NamedEnum)
[![License](https://poser.pugx.org/mesavolt/named-enum/license)](https://packagist.org/packages/mesavolt/named-enum)

## Usage

Add the package to your project :

```bash
composer require mesavolt/named-enum
```

Define a class that extends `Mesavolt\Enum\NamedEnum` with your enum values and names.
The values should be defined as class constants (their visibility is up to you) 
and the names should be declared in a protected static class variable `$VALUE_NAMES`,
indexed by the corresponding value.

> Quick note: By default, `NamedEnum::getName($value)` silently ignores undefined values and returns
> `null` for any undefined value. You can extend `Mesavolt\Enum\StrictNamedEnum`
> for a strict check that will throw an `\InvalidArgumentException` if you pass an undefined
> enum value to `getName($value)`.


```php
<?php

namespace App;


use Mesavolt\Enum\NamedEnum;

abstract class MyEnum extends NamedEnum
{
    public const FOO = 'foo';
    public const BAR = 'bar';
    
    protected static $VALUE_NAMES = [
        self::FOO => 'Foo name',
        self::BAR => 'Bar name',
    ];
}

```

Use it in your project :

```php
<?php

use App\MyEnum;

$object = new stdClass();
$object->foo = MyEnum::BAR;

echo MyEnum::getName($object->foo); // Bar name
```

Check [NamedEnum's public methods](src/Enum/NamedEnum.php) for more usages examples.


## Integration

### Symfony >=3 with Twig >1.26

If you use the default
[auto-configuring feature of Symfony introduced in Symfony 3.3](https://symfony.com/doc/current/service_container/3.3-di-changes.html),
you only need to register the `Mesavolt\Twig\NamedEnumExtension` as a service in your `services.yml` file.
Symfony will tag it properly to register it in the twig environment used by your app.

If you don't use the auto-configuring feature or if it's not available in your version,
you need to apply the tags manually when you register the extension as a service.

```yaml
# Symfony 3: app/config/services.yml
# Symfony 4: config/services.yaml
services:

    # Use this if you use the default auto-configuring feature of Symfony >=3.3  DI container
    Mesavolt\Twig\NamedEnumExtension: ~

    # Use this if you **don't** use the auto-configuring feature of Symfony >=3.3 DI container
    app.named_enum_extension:
        class: Mesavolt\Twig\NamedEnumExtension
        tags: { name: twig.extension }
```

Then, you can use the `enum_name` filter and the `enum_name` function provided by the extension, in your templates :

```php
<?php
// src/Controller/HomeController.php
// This is an example for Symfony 4.
// The code is exactly the same for Symfony 3, only the file locations change.
namespace App\Controller;


use App\Enum;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return $this->render('my-template.html.twig', [
            'value1' => Enum::FOO,
            'value2' => Enum::BAR
        ]);
    }
}
```

```twig
{# templates/my-template.html.twig #}

You selected "{{ value1|enum_name('\\App\\MyEnum') }}"
{# You selected "Foo name" #}


You selected {"{ enum_name(value2, '\\App\\MyEnum') }}"
{# You selected "Bar name" #}

```


## Testing

```bash
composer dump-autoload # make sure vendor/autoload.php exists
./vendor/bin/phpunit
```
