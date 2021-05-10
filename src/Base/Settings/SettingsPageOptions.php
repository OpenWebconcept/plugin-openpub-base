<?php

namespace OWC\OpenPub\Base\Settings;

class SettingsPageOptions
{
    /**
     * Settings defined on settings page
     *
     * @var array
     */
    private $settings;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * URL to the portal website.
     *
     * @return string
     */
    public function getPortalURL(): string
    {
        return $this->settings['_owc_setting_portal_url'] ?? '';
    }

    /**
     * @return string
     */
    public function getPortalItemSlug(): string
    {
        return $this->settings['_owc_setting_portal_openpub_item_slug'] ?? '';
    }

    /**
     * @return string
     */
    public function usePortalURL(): string
    {
        return $this->settings['_owc_setting_use_portal_url'] ?? '';
    }

    public function useEscapeElement(): bool
    {
        return $this->settings['_owc_setting_use_escape_element'] ?? false;
    }

    public function useShowOn(): bool
    {
        return $this->settings['_owc_setting_openpub_enable_show_on'] ?? false;
    }

    public static function make(): self
    {
        $defaultSettings = [
            '_owc_setting_portal_url'               => '',
            '_owc_setting_portal_openpub_item_slug' => '',
            '_owc_setting_use_portal_url'           => 0,
            '_owc_setting_use_escape_element'       => 0,
            '_owc_setting_openpub_enable_show_on'   => false
        ];

        return new static(wp_parse_args(get_option('_owc_openpub_base_settings'), $defaultSettings));
    }
}
