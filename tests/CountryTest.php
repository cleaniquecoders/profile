<?php

namespace CleaniqueCoders\Profile\Tests;

use Illuminate\Support\Facades\Schema;

class CountryTest extends TestCase
{
    /** @test */
    public function has_countries_table()
    {
        $this->assertTrue(Schema::hasTable('countries'));
    }

    /** @test */
    public function has_countries_data()
    {
        $countries = \DB::table('countries')->count();
        $this->assertEquals(241, $countries);
    }
}
