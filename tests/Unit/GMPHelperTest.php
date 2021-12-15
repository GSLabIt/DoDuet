<?php

namespace Tests\Unit;

use App\Http\Wrappers\GMPHelper;
use PHPUnit\Framework\TestCase;

class GMPHelperTest extends TestCase
{
    public function test_gmp_helper_can_initialize()
    {
        $this->assertTrue(GMPHelper::init("0") instanceof GMPHelper);
    }

    public function test_gmp_helper_return_its_value()
    {
        $this->assertEquals("0", (string) GMPHelper::init("0"));
        $this->assertEquals("0", GMPHelper::init("0")->raw());
    }

    public function test_gmp_helper_can_be_summed()
    {
        $this->assertEquals("2", (string) GMPHelper::init("1")->add(1));
    }

    public function test_gmp_helper_can_be_subtracted()
    {
        $this->assertEquals("0", (string) GMPHelper::init("1")->sub(1));
    }

    public function test_gmp_helper_can_be_divided()
    {
        $this->assertEquals("2", (string) GMPHelper::init("4")->div(2));
    }

    public function test_gmp_helper_can_be_multiplied()
    {
        $this->assertEquals("2", (string) GMPHelper::init("1")->mul(2));
    }

    public function test_gmp_helper_can_be_powered()
    {
        $this->assertEquals("4", (string) GMPHelper::init("2")->pow(2));
    }

    public function test_gmp_helper_can_be_moduled()
    {
        $this->assertEquals("1", (string) GMPHelper::init("3")->mod(2));
    }

    public function test_gmp_helper_format_decimal()
    {
        $this->assertEquals("1", GMPHelper::init("1")->format());
        $this->assertEquals("1.1", GMPHelper::init("11")->format(1));
        $this->assertEquals("1.11", GMPHelper::init("111")->format(2));
        $this->assertEquals("11.11", GMPHelper::init("1111")->format(2));
        $this->assertEquals("0.0011", GMPHelper::init("11")->format(4));
    }

    public function test_gmp_helper_comparison() {
        $this->assertTrue(GMPHelper::eq("1", "1"));
        $this->assertTrue(GMPHelper::neq("1", "2"));
        $this->assertTrue(GMPHelper::gt("2", "1"));
        $this->assertFalse(GMPHelper::gt("2", "2"));
        $this->assertTrue(GMPHelper::lt("1", "2"));
        $this->assertFalse(GMPHelper::lt("2", "2"));
    }
}
