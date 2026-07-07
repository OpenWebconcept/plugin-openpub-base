<?php

namespace OWC\OpenPub\Tests\Base\Metabox;

use CMB2;
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
        m::close();
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

        $plugin->loader->shouldReceive('addAction')->withArgs(function ($hook, $notice, $method, $priority, $args) {
            return 'admin_notices' === $hook
                && $notice instanceof AdminNotice
                && 'upgradeAdminNotice' === $method
                && 10 === $priority
                && 0 === $args;
        })->once();

        $service->register();

        // Setting '_owc_setting_use_escape_element' is enabled, so the escape element metabox is merged in.
        $configMetaboxes = [
            'base' => [
                'id'     => 'metadata',
                'fields' => [
                    'general' => [
                        'testfield_noid' => [
                            'type' => 'heading'
                        ],
                        'testfield1' => [
                            'id' => 'metabox_id1'
                        ]
                    ]
                ]
            ]
        ];

        $escapeElementMetabox = [
            'escape_element' => [
                'id'     => 'escape_element',
                'fields' => [
                    'general' => [
                        'testfield2' => [
                            'id' => 'metabox_id2'
                        ]
                    ]
                ]
            ]
        ];

        $config->shouldReceive('get')->with('cmb2_metaboxes')->once()->andReturn($configMetaboxes);
        $config->shouldReceive('get')->with('escape_element_metabox')->once()->andReturn($escapeElementMetabox);

        $createdBoxes = [];

        \WP_Mock::userFunction('new_cmb2_box')->andReturnUsing(function (array $args) use (&$createdBoxes) {
            $box = new CMB2($args);
            $createdBoxes[] = $box;

            return $box;
        });

        $service->registerMetaboxes();

        $this->assertCount(2, $createdBoxes);

        $metadataBox = $createdBoxes[0];
        $this->assertSame('metadata', $metadataBox->args['id']);
        $this->assertArrayNotHasKey('fields', $metadataBox->args);
        $this->assertEquals([
            ['type' => 'heading'],
            ['id' => MetaboxServiceProvider::PREFIX . 'metabox_id1']
        ], $metadataBox->fields);

        $escapeElementBox = $createdBoxes[1];
        $this->assertSame('escape_element', $escapeElementBox->args['id']);
        $this->assertEquals([
            ['id' => MetaboxServiceProvider::PREFIX . 'metabox_id2']
        ], $escapeElementBox->fields);
    }
}
