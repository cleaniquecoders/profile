<?php

namespace CleaniqueCoders\Profile\Tests;

use Illuminate\Support\Facades\Schema;

class PhoneTypeTest extends TestCase
{
    /** @test */
    public function it_has_config()
    {
        $this->assertTrue(! empty(config('profile')));
        $this->assertTrue(in_array('PhoneTypeSeeder', config('profile.seeders')));
    }

    /** @test */
    public function has_phone_types_table()
    {
        $this->assertTrue(Schema::hasTable('phone_types'));
    }

    /** @test */
    public function has_phone_types()
    {
        $phone_types = \DB::table('phone_types')->count();
        $this->assertEquals(4, $phone_types);
    }
}
