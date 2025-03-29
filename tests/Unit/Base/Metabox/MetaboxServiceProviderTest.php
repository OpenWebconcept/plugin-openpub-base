<?php

namespace OWC\OpenPub\Tests\Base\Metabox;

use Mockery as m;
use OWC\OpenPub\Base\Foundation\Config;
use OWC\OpenPub\Base\Foundation\Loader;
use OWC\OpenPub\Base\Foundation\Plugin;
use OWC\OpenPub\Base\Metabox\AdminNotice;
use OWC\OpenPub\Base\Metabox\MetaboxServiceProvider;
use OWC\OpenPub\Tests\TestCase;
use WP_Mock;

class MetaboxServiceProviderTest extends TestCase
{
    protected function setUp(): void
    {
        WP_Mock::setUp();

        \WP_Mock::userFunction('wp_parse_args', [
            'return' => [
                '_owc_setting_portal_url'                      => '',
                '_owc_setting_portal_openpub_item_slug'        => '',
                '_owc_setting_use_portal_url'                  => 0,
                '_owc_setting_use_escape_element'              => 1,
                '_owc_setting_openpub_expired_auto'            => 0,
                '_owc_setting_openpub_expired_auto_after_days' => 0
            ]
        ]);

        \WP_Mock::userFunction('get_option', [
            'return' => [
                '_owc_setting_portal_url'                      => '',
                '_owc_setting_portal_openpub_item_slug'        => '',
                '_owc_setting_use_portal_url'                  => 0,
                '_owc_setting_use_escape_element'              => 1,
                '_owc_setting_openpub_expired_auto'            => 0,
                '_owc_setting_openpub_expired_auto_after_days' => 0
            ]
        ]);
    }

    protected function tearDown(): void
    {
        WP_Mock::tearDown();
    }

    /** @test */
    public function check_registration_of_metaboxes()
    {
        $config = m::mock(Config::class);
        $plugin = m::mock(Plugin::class);

        $plugin->config = $config;
        $plugin->loader = m::mock(Loader::class);

        $service = new MetaboxServiceProvider($plugin);

        $plugin->loader->shouldReceive('addAction')->withArgs([
            'cmb2_admin_init',
            $service,
            'registerMetaboxes',
            10,
            0
        ])->once();

        $plugin->loader->shouldReceive('addAction')->withArgs([
            'admin_notices',
            \Mockery::type(AdminNotice::class),
            'upgradeAdminNotice',
            10,
            0
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
            ],
            'escape_element' => [
                'id'     => 'escape_element',
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

        $config->shouldReceive('get')->with('cmb2_metaboxes')->once()->andReturn($configMetaboxes['base']);
        $config->shouldReceive('get')->with('escape_element_metabox')->once()->andReturn($configMetaboxes['escape_element']);

        $cmb2 = \Mockery::mock('CMB2');
        $cmb2->shouldReceive('add_field')->with(['type' => 'heading']);
        $cmb2->shouldReceive('add_field')->with(['id' => '_owc_metabox_id1']);
        $cmb2->shouldReceive('add_field')->with(['id' => '_owc_metabox_id2']);

        \WP_Mock::userFunction('new_cmb2_box', [
            'return' => $cmb2,
        ]);

        $service->registerMetaboxes();

        $this->expectNotToPerformAssertions();
    }
}
