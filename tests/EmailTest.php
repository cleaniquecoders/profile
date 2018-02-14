<?php

namespace CleaniqueCoders\Profile\Tests;

use Illuminate\Support\Facades\Schema;

class EmailTest extends TestCase
{
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
            'email'       => 'info@cleaniquecoders.com',
            'is_default'  => true,
        ]);
        $this->assertNotNull($email);
        $this->assertEquals('info@cleaniquecoders.com', $email->email);
        $this->assertTrue($email->is_default);
    }
}
