<?php

namespace OWC\OpenPub\Base\PostType;

use Mockery as m;
use OWC\OpenPub\Base\Foundation\Config;
use OWC\OpenPub\Base\Foundation\Loader;
use OWC\OpenPub\Base\Foundation\Plugin;
use OWC\OpenPub\Base\Tests\Unit\TestCase;
use WP_Mock;

class PostTypeServiceProviderTest extends TestCase
{
    protected function setUp(): void
    {
        WP_Mock::setUp();
    }

    protected function tearDown(): void
    {
        WP_Mock::tearDown();
    }

    /** @test */
    public function check_registration_of_posttypes()
    {
        $config = m::mock(Config::class);
        $loader = m::mock(Loader::class);
        $plugin = m::mock(Plugin::class);

        $plugin->shouldReceive('make')->with('loader')->andReturn($loader);
        $plugin->shouldReceive('make')->with('config')->andReturn($config);

        $service = new PostTypeServiceProvider($plugin);

        $plugin->make('loader')->shouldReceive('addAction')->withArgs([
            'init',
            $service,
            'registerPostTypes',
        ])->once();

        $plugin->make('loader')->shouldReceive('addAction')->withArgs([
            'pre_get_posts',
            $service,
            'orderByPublishedDate',
        ])->once();

        /**
         * Examples of registering post types: http://johnbillion.com/extended-cpts/
         */
        $configPostTypes = [
            'posttype' => [
                'args' => [
                ],
                'names' => [
                ],
            ],
        ];

        $service->register();

        $this->assertTrue(true);
    }
}
