<?php

namespace CleaniqueCoders\Profile\Tests;

use Illuminate\Support\Facades\Schema;

class CountryTest extends TestCase
{
    /** @test */
    public function it_has_config()
    {
        $this->assertTrue(! empty(config('profile')));
        $this->assertContains('CountrySeeder', config('profile.seeders'));
    }

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
