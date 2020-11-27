<?php

namespace OWC\OpenPub\Base\Metabox;

use Mockery as m;
use OWC\OpenPub\Base\Foundation\Config;
use OWC\OpenPub\Base\Foundation\Loader;
use OWC\OpenPub\Base\Foundation\Plugin;
use OWC\OpenPub\Base\Tests\Unit\TestCase;
use WP_Mock;

class MetaboxServiceProviderTest extends TestCase
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
    public function check_registration_of_metaboxes()
    {
        $config = m::mock(Config::class);
        $loader = m::mock(Loader::class);
        $plugin = m::mock(Plugin::class);

        $plugin->shouldReceive('make')->with('loader')->andReturn($loader);
        $plugin->shouldReceive('make')->with('config')->andReturn($config);

        $service = new MetaboxServiceProvider($plugin);

        $plugin->make('loader')->shouldReceive('addFilter')->withArgs([
            'rwmb_meta_boxes',
            $service,
            'registerMetaboxes',
            10,
            1
        ])->once();

        $service->register();

        $configMetaboxes = [
            'base' => [
                'id'     => 'metadata',
                'fields' => [
                    'general' => [
                        'testfield_noid' => [
                            'type' => 'heading'
                        ],
                        'testfield1'     => [
                            'id' => 'metabox_id1'
                        ],
                        'testfield2'     => [
                            'id' => 'metabox_id2'
                        ]
                    ]
                ]
            ]
        ];

        $prefix = MetaboxServiceProvider::PREFIX;

        $expectedMetaboxes = [
            0 => [
                'id'     => 'metadata',
                'fields' => [
                    [
                        'type' => 'heading'
                    ],
                    [
                        'id' => $prefix . 'metabox_id1'
                    ],
                    [
                        'id' => $prefix . 'metabox_id2'
                    ]
                ]
            ]
        ];

        $config->shouldReceive('get')->with('metaboxes')->once()->andReturn($configMetaboxes);

        //test for filter being called
        \WP_Mock::expectFilter('owc/openpub/base/before-register-metaboxes', $expectedMetaboxes);

        $this->assertEquals($expectedMetaboxes, $service->registerMetaboxes([]));

        $existingMetaboxes = [
            0 => [
                'id'     => 'existing_metadata',
                'fields' => [
                    [
                        'type' => 'existing_heading'
                    ],
                    [
                        'id' => $prefix . 'existing_metabox_id1'
                    ],
                    [
                        'id' => $prefix . 'existing_metabox_id2'
                    ]
                ]
            ]
        ];

        $expectedMetaboxesAfterMerge = [

            0 => [
                'id'     => 'existing_metadata',
                'fields' => [
                    [
                        'type' => 'existing_heading'
                    ],
                    [
                        'id' => $prefix . 'existing_metabox_id1'
                    ],
                    [
                        'id' => $prefix . 'existing_metabox_id2'
                    ]
                ]
            ],
            1 => [
                'id'     => 'metadata',
                'fields' => [
                    [
                        'type' => 'heading'
                    ],
                    [
                        'id' => $prefix . 'metabox_id1'
                    ],
                    [
                        'id' => $prefix . 'metabox_id2'
                    ]
                ]
            ]
        ];

        $config->shouldReceive('get')->with('metaboxes')->once()->andReturn($configMetaboxes);

        $this->assertEquals($expectedMetaboxesAfterMerge, $service->registerMetaboxes($existingMetaboxes));
    }
}
