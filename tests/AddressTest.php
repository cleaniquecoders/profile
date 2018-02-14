<?php

namespace CleaniqueCoders\Profile\Tests;

use Illuminate\Support\Facades\Schema;

class AddressTest extends TestCase
{
    /** @test */
    public function it_has_addresses_table()
    {
        $this->assertTrue(Schema::hasTable('addresses'));
    }

    /** @test */
    public function it_has_no_addresses_records()
    {
        $addresses = \DB::table('addresses')->count();
        $this->assertEquals(0, $addresses);
    }

    /** @test */
    public function it_can_create_address()
    {
        $address = $this->user->addresses()->create([
            'primary'       => 'OSTIA, Bangi',
            'city'          => 'Bandar Baru Bangi',
            'state'         => 'Selangor',
            'country_id'    => 131,
            'is_default'    => true,
        ]);
        $this->assertNotNull($address);
        $this->assertEquals('OSTIA, Bangi', $address->primary);
        $this->assertEquals('Bandar Baru Bangi', $address->city);
        $this->assertEquals('Selangor', $address->state);
        $this->assertEquals(131, $address->country_id);
        $this->assertTrue($address->is_default);
    }
}
