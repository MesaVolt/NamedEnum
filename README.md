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

## Testing

```bash
composer dump-autoload # make sure vendor/autoload.php exists
./vendor/bin/phpunit
```
