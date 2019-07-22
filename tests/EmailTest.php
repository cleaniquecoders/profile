<?php

namespace CleaniqueCoders\Profile\Tests;

use Illuminate\Support\Facades\Schema;

class EmailTest extends TestCase
{
    /**
     * @var string
     */
    protected $get_actual_config_key = 'email';

    /**
     * @var string
     */
    protected $get_expected_config_key = 'email';

    /**
     * @var string
     */
    protected $get_actual_config_model_class = '\CleaniqueCoders\Profile\Models\Email::class';

    /**
     * @var string
     */
    protected $get_expected_config_model_class = '\CleaniqueCoders\Profile\Models\Email::class';

    /**
     * @var string
     */
    protected $get_actual_config_type = 'emailable';

    /**
     * @var string
     */
    protected $get_expected_config_type = 'emailable';

    /** @test */
    public function has_emails_table()
    {
        $this->assertTrue(Schema::hasTable('emails'));
    }

    /** @test */
    public function it_has_no_emails()
    {
        $emails = \DB::table('emails')->count();
        $this->assertEquals(0, $emails);
    }

    /** @test */
    public function it_can_create_email()
    {
        $email = $this->user->emails()->create([
            'email'      => 'info@cleaniquecoders.com',
            'is_default' => true,
        ]);
        $this->assertNotNull($email);
        $this->assertEquals('info@cleaniquecoders.com', $email->email);
        $this->assertTrue($email->is_default);
    }
}
