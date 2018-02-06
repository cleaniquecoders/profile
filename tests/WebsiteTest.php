<?php

namespace CleaniqueCoders\Profile\Tests;

use Illuminate\Support\Facades\Schema;

class WebsiteTest extends TestCase
{
    /** @test */
    public function has_websites_table()
    {
        $this->assertTrue(Schema::hasTable('websites'));
    }

    /** @test */
    public function has_websites()
    {
        $websites = \DB::table('websites')->count();
        $this->assertEquals(0, $websites);
    }

    /** @test */
    public function it_can_create_website()
    {
        $website = $this->user->websites()->create([
            'name'       => 'Cleanique Coders',
            'url'        => 'https://cleaniquecoders.com',
            'is_default' => true,
        ]);
        $this->assertNotNull($website);
        $this->assertEquals('Cleanique Coders', $website->name);
        $this->assertEquals('https://cleaniquecoders.com', $website->url);
        $this->assertTrue($website->is_default);
    }
}
