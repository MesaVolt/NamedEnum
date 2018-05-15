<?php
declare(strict_types=1);

namespace Mesavolt\Tests\Enum;


use Mesavolt\Tests\Fixture\StrictTestEnum;
use PHPUnit\Framework\TestCase;

class StrictNamedEnumTest extends TestCase
{
    public function testCanGetNameFromValue()
    {
        $this->assertEquals('FOO NAME', StrictTestEnum::getName(StrictTestEnum::FOO));
    }

    public function testThrowsExeptionForNonExistingValue()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Undefined value "foo" for enum Mesavolt\Tests\Fixture\StrictTestEnum');
        StrictTestEnum::getName('foo');
    }
}
