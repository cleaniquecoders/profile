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
        $this->assertEquals(5, $phone_types);
    }

    /** @test */
    public function has_common_phone_types_config()
    {
        $this->assertTrue(! empty(config('profile.data.phoneType')));
        $types = [
            'Home',
            'Mobile',
            'Office',
            'Other',
            'Fax',
        ];
        foreach ($types as $type) {
            $this->assertTrue(in_array($type, config('profile.data.phoneType')));
        }
    }

    /** @test */
    public function has_common_phone_types()
    {
        $this->artisan('db:seed', [
            '--class' => \PhoneTypeSeeder::class,
        ]);

        $this->assertDatabaseHas('phone_types', [
            'name' => 'Home',
        ]);

        $this->assertDatabaseHas('phone_types', [
            'name' => 'Mobile',
        ]);

        $this->assertDatabaseHas('phone_types', [
            'name' => 'Office',
        ]);

        $this->assertDatabaseHas('phone_types', [
            'name' => 'Other',
        ]);

        $this->assertDatabaseHas('phone_types', [
            'name' => 'Fax',
        ]);
    }
}
