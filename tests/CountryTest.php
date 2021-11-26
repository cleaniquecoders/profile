<?php

namespace CleaniqueCoders\Profile\Tests;

use Illuminate\Support\Facades\Schema;

class CountryTest extends TestCase
{
    /** @test */
    public function itHasConfig()
    {
        $this->assertTrue(! empty(config('profile')));
        $this->assertContains('CleaniqueCoders\Profile\Database\Seeders\CountrySeeder', config('profile.seeders'));
    }

    /** @test */
    public function hasCountriesTable()
    {
        $this->assertTrue(Schema::hasTable('countries'));
    }

    /** @test */
    public function hasCountriesData()
    {
        $countries = \DB::table('countries')->count();
        $this->assertEquals(241, $countries);
    }
}
