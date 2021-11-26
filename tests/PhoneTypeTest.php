<?php

namespace CleaniqueCoders\Profile\Tests;

use Illuminate\Support\Facades\Schema;

class PhoneTypeTest extends TestCase
{
    /** @test */
    public function itHasConfig()
    {
        $this->assertTrue(! empty(config('profile')));
        $this->assertContains('CleaniqueCoders\Profile\Database\Seeders\PhoneTypeSeeder', config('profile.seeders'));
    }

    /** @test */
    public function hasPhoneTypesTable()
    {
        $this->assertTrue(Schema::hasTable('phone_types'));
    }

    /** @test */
    public function hasPhoneTypes()
    {
        $phone_types = \DB::table('phone_types')->count();
        $this->assertEquals(5, $phone_types);
    }

    /** @test */
    public function hasCommonPhoneTypesConfig()
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
            $this->assertContains($type, config('profile.data.phoneType'));
        }
    }

    /** @test */
    public function hasCommonPhoneTypes()
    {
        $this->artisan('db:seed', [
            '--class' => \CleaniqueCoders\Profile\Database\Seeders\PhoneTypeSeeder::class,
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
