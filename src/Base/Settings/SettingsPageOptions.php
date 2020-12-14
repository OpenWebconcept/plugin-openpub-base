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

    final private function __construct(array $settings = [])
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

    public static function make(array $settings = []): self
    {
        $defaultSettings = [
            '_owc_setting_portal_url'                       => '',
            '_owc_setting_portal_openpub_item_slug'         => '',
        ];

        return new static(\wp_parse_args($settings, $defaultSettings));
    }
}
