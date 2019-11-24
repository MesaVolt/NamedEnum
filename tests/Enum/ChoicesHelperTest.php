<?php

namespace Mesavolt\Tests\Enum;


use Mesavolt\Enum\ChoicesHelper;
use Mesavolt\Tests\Fixture\TestEnum;
use PHPUnit\Framework\TestCase;

class ChoicesHelperTest extends TestCase
{
    public function testExcept()
    {
        $choices = TestEnum::choices();
        $only = ChoicesHelper::except([TestEnum::VALUE_2], $choices);

        $this->assertCount(2, $only);

        $this->assertContains(TestEnum::VALUE_1, $only);
        $this->assertContains(TestEnum::VALUE_STRING, $only);

        $this->assertArrayHasKey(TestEnum::getName(TestEnum::VALUE_1), $only);
        $this->assertArrayHasKey(TestEnum::getName(TestEnum::VALUE_STRING), $only);

        $only = ChoicesHelper::except([], $choices);
        $this->assertEquals($choices, $only);
    }

    public function testOnly()
    {
        $choices = TestEnum::choices();
        $only = ChoicesHelper::only([TestEnum::VALUE_1, TestEnum::VALUE_STRING], $choices);

        $this->assertCount(2, $only);

        $this->assertContains(TestEnum::VALUE_1, $only);
        $this->assertContains(TestEnum::VALUE_STRING, $only);

        $this->assertArrayHasKey(TestEnum::getName(TestEnum::VALUE_1), $only);
        $this->assertArrayHasKey(TestEnum::getName(TestEnum::VALUE_STRING), $only);

        $only = ChoicesHelper::only([], $choices);
        $this->assertEquals([], $only);}
}
