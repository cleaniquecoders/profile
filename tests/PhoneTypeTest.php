<?php

namespace CleaniqueCoders\Profile\Tests;

class PhoneTypeTest extends TestCase
{
    /** @test */
    public function has_phone_types()
    {
        $phone_types = \DB::table('phone_types')->count();
        $this->assertEquals(4, $phone_types);
    }
}
