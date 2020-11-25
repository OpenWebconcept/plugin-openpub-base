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

        \WP_Mock::userFunction('wp_parse_args', [
            'return' => [
                '_owc_setting_portal_url'                       => '',
                '_owc_setting_portal_openpub_item_slug'         => '',
                '_owc_setting_use_portal_url'                   => 0,
            ]
        ]);

        \WP_Mock::userFunction('get_option', [
            'return' => [
                '_owc_setting_portal_url'                       => '',
                '_owc_setting_portal_openpub_item_slug'         => '',
                '_owc_setting_use_portal_url'                   => 0,
            ]
        ]);
    }

    protected function tearDown(): void
    {
        WP_Mock::tearDown();
    }

    /** @test */
    public function check_registration_of_posttypes()
    {
        $config = m::mock(Config::class);
        $plugin = m::mock(Plugin::class);

        $plugin->config = $config;
        $plugin->loader = m::mock(Loader::class);

        $service = new PostTypeServiceProvider($plugin);

        $plugin->loader->shouldReceive('addAction')->withArgs([
            'init',
            $service,
            'registerPostTypes',
        ])->once();

        $plugin->loader->shouldReceive('addAction')->withArgs([
            'pre_get_posts',
            $service,
            'orderByPublishedDate',
        ])->once();

        /**
         * Examples of registering post types: http://johnbillion.com/extended-cpts/
         */
        $configPostTypes = [
            'posttype' => [
                'args' => [],
                'names' => [],
            ],
        ];

        $service->register();

        $this->assertTrue(true);
    }
}
