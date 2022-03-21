<?php

namespace OWC\PDC\Tests\Base\Settings;

use OWC\OpenPub\Base\Settings\SettingsPageOptions;
use OWC\OpenPub\Tests\TestCase;

class SettingsPageOptionsTest extends TestCase
{
    /** @var SettingsPageOptions */
    private $settingsPageOptions;

    public function setUp(): void
    {
        \WP_Mock::setUp();

        $this->settingsPageOptions = new SettingsPageOptions([
            '_owc_setting_portal_url'               => 'www.test.nl',
            '_owc_setting_portal_openpub_item_slug' => 'direct/regelen',
            '_owc_setting_use_portal_url'           => 0,
            '_owc_setting_use_escape_element'       => 0,
            '_owc_setting_openpub_expired_auto_after_days' => 0
        ]);
    }

    public function tearDown(): void
    {
        \WP_Mock::tearDown();
    }

    public function do_not_use_auto_expiration_after_days(): void
    {
        $expectedResult = 0;
        $result = $this->settingsPageOptions->expireAfter();

        $this->assertTrue($expectedResult === $result);
    }

    /** @test */
    public function do_not_use_escape_element_setting(): void
    {
        $expectedResult = false;
        $result = $this->settingsPageOptions->useEscapeElement();

        $this->assertEquals($expectedResult, $result);
    }

    /** @test */
    public function portal_url_has_value(): void
    {
        $expectedResult = 'www.test.nl';
        $result = $this->settingsPageOptions->getPortalURL();

        $this->assertEquals($expectedResult, $result);
    }

    /** @test */
    public function portal_url_has_no_value(): void
    {
        $expectedResult = '';
        $result = $this->settingsPageOptions->getPortalURL();

        $this->assertNotEquals($expectedResult, $result);
    }

    /** @test */
    public function portal_item_slug_has_value(): void
    {
        $expectedResult = 'direct/regelen';
        $result = $this->settingsPageOptions->getPortalItemSlug();

        $this->assertEquals($expectedResult, $result);
    }

    /** @test */
    public function portal_item_slug_has_no_value(): void
    {
        $expectedResult = '';
        $result = $this->settingsPageOptions->getPortalItemSlug();

        $this->assertNotEquals($expectedResult, $result);
    }
}
