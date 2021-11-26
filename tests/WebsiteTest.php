<?php

namespace CleaniqueCoders\Profile\Tests;

use Illuminate\Support\Facades\Schema;

class WebsiteTest extends TestCase
{
    /**
     * @var string
     */
    protected $get_actual_config_key = 'website';

    /**
     * @var string
     */
    protected $get_expected_config_key = 'website';

    /**
     * @var string
     */
    protected $get_actual_config_model_class = '\CleaniqueCoders\Profile\Models\Website::class';

    /**
     * @var string
     */
    protected $get_expected_config_model_class = '\CleaniqueCoders\Profile\Models\Website::class';

    /**
     * @var string
     */
    protected $get_actual_config_type = 'websiteable';

    /**
     * @var string
     */
    protected $get_expected_config_type = 'websiteable';

    /** @test */
    public function hasWebsitesTable()
    {
        $this->assertTrue(Schema::hasTable('websites'));
    }

    /** @test */
    public function hasWebsites()
    {
        $websites = \DB::table('websites')->count();
        $this->assertEquals(0, $websites);
    }

    /** @test */
    public function itCanCreateWebsite()
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
