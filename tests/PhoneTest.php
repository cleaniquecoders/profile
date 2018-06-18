<?php

namespace CleaniqueCoders\Profile\Tests;

use Illuminate\Support\Facades\Schema;

class PhoneTest extends TestCase
{
    /** @test */
    public function it_has_phones_table()
    {
        $this->assertTrue(Schema::hasTable('phones'));
    }

    /** @test */
    public function it_can_create_phone()
    {
        $phone = $this->user->phones()->create([
            'phone_number' => '+6089259167',
            'is_default'   => true,
        ]);
        $this->assertNotNull($phone);
        $this->assertEquals('+6089259167', $phone->phone_number);
        $this->assertTrue($phone->is_default);
    }
}
