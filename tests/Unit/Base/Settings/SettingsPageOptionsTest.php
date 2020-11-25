<?php

namespace OWC\PDC\Base\Settings;

use Mockery as m;
use OWC\OpenPub\Base\Settings\SettingsPageOptions;
use OWC\OpenPub\Base\Tests\Unit\TestCase;

class SettingsPageOptionsTest extends TestCase
{
    /** @var SettingsPageOptions */
    private $settingsPageOptions;

    public function setUp(): void
    {
        \WP_Mock::setUp();

        $this->settingsPageOptions = new SettingsPageOptions([
            '_owc_setting_portal_url'                       => 'www.test.nl',
            '_owc_setting_portal_openpub_item_slug'         => 'direct/regelen',
            '_owc_setting_use_portal_url'                   => 0,
        ]);
    }

    public function tearDown(): void
    {
        \WP_Mock::tearDown();
    }

    /** @test */
    public function portal_url_has_value(): void
    {
        $expectedResult = 'www.test.nl';
        $result         = $this->settingsPageOptions->getPortalURL();

        $this->assertEquals($expectedResult, $result);
    }

    /** @test */
    public function portal_url_has_no_value(): void
    {
        $expectedResult = '';
        $result         = $this->settingsPageOptions->getPortalURL();

        $this->assertNotEquals($expectedResult, $result);
    }

    /** @test */
    public function portal_item_slug_has_value(): void
    {
        $expectedResult = 'direct/regelen';
        $result         = $this->settingsPageOptions->getPortalItemSlug();

        $this->assertEquals($expectedResult, $result);
    }

    /** @test */
    public function portal_item_slug_has_no_value(): void
    {
        $expectedResult = '';
        $result         = $this->settingsPageOptions->getPortalItemSlug();

        $this->assertNotEquals($expectedResult, $result);
    }
}
