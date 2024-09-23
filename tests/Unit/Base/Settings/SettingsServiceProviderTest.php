<?php

namespace OWC\OpenPub\Tests\Base\Settings;

use Mockery as m;
use OWC\OpenPub\Base\Foundation\Config;
use OWC\OpenPub\Base\Foundation\Loader;
use OWC\OpenPub\Base\Foundation\Plugin;
use OWC\OpenPub\Base\Settings\SettingsServiceProvider;
use OWC\OpenPub\Tests\TestCase;

class SettingsServiceProviderTest extends TestCase
{
    public function setUp(): void
    {
        \WP_Mock::setUp();

        \WP_Mock::userFunction('wp_parse_args', [
            'return' => [
                '_owc_setting_portal_url'                      => '',
                '_owc_setting_portal_openpub_item_slug'        => '',
                '_owc_setting_use_portal_url'                  => 0,
                '_owc_setting_use_escape_element'              => 0,
                '_owc_setting_openpub_enable_show_on'          => 0,
                '_owc_setting_openpub_expired_auto_after_days' => 0
            ]
        ]);

        \WP_Mock::userFunction('get_option', [
            'return' => [
                '_owc_setting_portal_url'                      => '',
                '_owc_setting_portal_openpub_item_slug'        => '',
                '_owc_setting_use_portal_url'                  => 0,
                '_owc_setting_use_escape_element'              => 0,
                '_owc_setting_openpub_enable_show_on'          => 0,
                '_owc_setting_openpub_expired_auto_after_days' => 0

            ]
        ]);
    }

    public function tearDown(): void
    {
        \WP_Mock::tearDown();
    }

    /** @test */
    public function check_registration_of_settings_metaboxes()
    {
        $config = m::mock(Config::class);
        $plugin = m::mock(Plugin::class);

        $plugin->config = $config;
        $plugin->loader = m::mock(Loader::class);

        $service = new SettingsServiceProvider($plugin);

        $plugin->loader->shouldReceive('addAction')->withArgs([
            'cmb2_render_number',
            $service,
            'registerMissingNumberField',
            10,
            5
        ])->once();

        $plugin->loader->shouldReceive('addAction')->withArgs([
            'cmb2_admin_init',
            $service,
            'registerSettingsPages',
            10,
            0
        ])->once();

        $cmb2 = \Mockery::mock('CMB2');

        \WP_Mock::userFunction('new_cmb2_box', [
            'return' => $cmb2,
        ]);

        $service->register();
        $service->registerSettingsPage([]);

        $existingSettingsPage = [
            0 => [
                'id'          => 'existing_settings_page',
                'option_name' => 'existing_settings_page'
            ]
        ];

        $service->registerSettingsPage($existingSettingsPage);

        $this->expectNotToPerformAssertions();
    }
}
