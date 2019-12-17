<?php

namespace CleaniqueCoders\Profile\Tests;

use CleaniqueCoders\Profile\Models\PhoneType;
use Illuminate\Support\Facades\Schema;

class PhoneTest extends TestCase
{
    /**
     * @var string
     */
    protected $get_actual_config_key = 'phone';

    /**
     * @var string
     */
    protected $get_expected_config_key = 'phone';

    /**
     * @var string
     */
    protected $get_actual_config_model_class = '\CleaniqueCoders\Profile\Models\Phone::class';

    /**
     * @var string
     */
    protected $get_expected_config_model_class = '\CleaniqueCoders\Profile\Models\Phone::class';

    /**
     * @var string
     */
    protected $get_actual_config_type = 'phoneable';

    /**
     * @var string
     */
    protected $get_expected_config_type = 'phoneable';

    /** @test */
    public function it_has_phones_table()
    {
        $this->assertTrue(Schema::hasTable('phones'));
    }

    /** @test */
    public function it_can_create_home_phone()
    {
        $phone = $this->user->phones()->create([
            'phone_number'  => '+6089259167',
            'is_default'    => true,
            'phone_type_id' => PhoneType::HOME,
        ]);

        $this->assertNotNull($phone);
        $this->assertEquals('+6089259167', $phone->phone_number);
        $this->assertTrue($phone->is_default);
        $this->assertEquals(PhoneType::HOME, $phone->phone_type_id);
    }

    /** @test */
    public function it_can_create_mobile_phone()
    {
        $phone = $this->user->phones()->create([
            'phone_number'  => '+60191234567',
            'is_default'    => true,
            'phone_type_id' => PhoneType::MOBILE,
        ]);
        $this->assertNotNull($phone);
        $this->assertEquals('+60191234567', $phone->phone_number);
        $this->assertTrue($phone->is_default);
        $this->assertEquals(PhoneType::MOBILE, $phone->phone_type_id);
    }

    /** @test */
    public function it_can_create_office_phone()
    {
        $phone = $this->user->phones()->create([
            'phone_number'  => '+60380001000',
            'is_default'    => true,
            'phone_type_id' => PhoneType::OFFICE,
        ]);
        $this->assertNotNull($phone);
        $this->assertEquals('+60380001000', $phone->phone_number);
        $this->assertTrue($phone->is_default);
        $this->assertEquals(PhoneType::OFFICE, $phone->phone_type_id);
    }

    /** @test */
    public function it_can_create_other_phone()
    {
        $phone = $this->user->phones()->create([
            'phone_number'  => '+60380001000',
            'is_default'    => true,
            'phone_type_id' => PhoneType::OTHER,
        ]);
        $this->assertNotNull($phone);
        $this->assertEquals('+60380001000', $phone->phone_number);
        $this->assertTrue($phone->is_default);
        $this->assertEquals(PhoneType::OTHER, $phone->phone_type_id);
    }

    /** @test */
    public function it_can_create_fax_phone()
    {
        $phone = $this->user->phones()->create([
            'phone_number'  => '+60380001001',
            'is_default'    => true,
            'phone_type_id' => PhoneType::FAX,
        ]);
        $this->assertNotNull($phone);
        $this->assertEquals('+60380001001', $phone->phone_number);
        $this->assertTrue($phone->is_default);
        $this->assertEquals(PhoneType::FAX, $phone->phone_type_id);
    }
}
