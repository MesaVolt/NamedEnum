# Named enum for PHP 7.1 +

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

## Testing

```bash
composer dump-autoload # make sure vendor/autoload.php exists
./vendor/bin/phpunit
```
