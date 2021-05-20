<?php
declare(strict_types=1);

namespace Mesavolt\Tests\Enum;


use Mesavolt\Tests\Fixture\StrictTestEnum;
use PHPUnit\Framework\TestCase;

class StrictNamedEnumTest extends TestCase
{
    public function testCanGetNameFromValue(): void
    {
        self::assertEquals('FOO NAME', StrictTestEnum::getName(StrictTestEnum::FOO));
    }

    public function testThrowsExeptionForNonExistingValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Undefined value "foo" for enum Mesavolt\Tests\Fixture\StrictTestEnum');
        StrictTestEnum::getName('foo');
    }
}
