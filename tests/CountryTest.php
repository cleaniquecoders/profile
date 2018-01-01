<?php

namespace CLNQCDRS\Profile\Tests;

class CountryTest extends TestCase
{
    /** @test */
    public function has_countries()
    {
        $countries = \DB::table('countries')->count();
        $this->assertEquals(241, $countries);
    }
}
