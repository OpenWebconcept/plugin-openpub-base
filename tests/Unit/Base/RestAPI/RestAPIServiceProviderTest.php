<?php

namespace OWC\OpenPub\Base\RestAPI;

use Mockery as m;
use OWC\OpenPub\Base\Config;
use OWC\OpenPub\Base\Foundation\Loader;
use OWC\OpenPub\Base\Foundation\Plugin;
use OWC\OpenPub\Base\Tests\Unit\TestCase;
use WP_Mock;

class RestAPIServiceProviderTest extends TestCase
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
    public function check_registration_of_rest_endpoints()
    {
        $config = m::mock(Config::class);
        $loader = m::mock(Loader::class);
        $plugin = m::mock(Plugin::class);

        $plugin->shouldReceive('make')->with('loader')->andReturn($loader);
        $plugin->shouldReceive('make')->with('config')->andReturn($config);

        $service = new RestAPIServiceProvider($plugin);

        $plugin->make('loader')->shouldReceive('addFilter')->withArgs([
            'owc/config-expander/rest-api/whitelist',
            $service,
            'whitelist',
            10,
            1
        ])->once();

        $plugin->make('loader')->shouldReceive('addFilter')->withArgs([
            'rest_api_init',
            $service,
            'registerRoutes'
        ])->once();

        $configRestAPIFields = [
            'posttype1' => [
                'endpoint_field1' =>
                    [
                        'get_callback'    => ['object', 'callback1'],
                        'update_callback' => null,
                        'schema'          => null,
                    ],
                'endpoint_field2' =>
                    [
                        'get_callback'    => ['object', 'callback2'],
                        'update_callback' => null,
                        'schema'          => null,
                    ]
            ],
            'posttype2' => [
                'endpoint_field1' =>
                    [
                        'get_callback'    => ['object', 'callback1'],
                        'update_callback' => null,
                        'schema'          => null,
                    ],
                'endpoint_field2' =>
                    [
                        'get_callback'    => ['object', 'callback2'],
                        'update_callback' => null,
                        'schema'          => null,
                    ]
            ]
        ];

        WP_Mock::userFunction(
            'post_type_exists',
            [
                'args'   => [WP_Mock\Functions::anyOf('posttype1', 'posttype2')],
                'times'  => '0+',
                'return' => true
            ]
        );

        WP_Mock::passthruFunction('register_rest_route', [
            'times'  => '0+',
            'return' => true
        ]);

        $config->shouldReceive('get')->withArgs(['api.models'])->andReturn([])->once();

        $service->register();

        $this->assertTrue(true);
    }
}
