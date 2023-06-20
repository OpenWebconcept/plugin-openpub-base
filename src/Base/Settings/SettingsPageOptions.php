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
     */
    public function getPortalURL(): string
    {
        return $this->settings['_owc_setting_portal_url'] ?? '';
    }

    public function getPortalItemSlug(): string
    {
        return $this->settings['_owc_setting_portal_openpub_item_slug'] ?? '';
    }

    public function isPortalSlugValid(): bool
    {
        return ! empty($this->getPortalURL()) && ! empty($this->getPortalItemSlug());
    }

    public function usePortalURL(): bool
    {
        $setting = $this->settings['_owc_setting_use_portal_url'] ?? false;

        return filter_var($setting, FILTER_VALIDATE_BOOLEAN);
    }

    public function useEscapeElement(): bool
    {
        $setting = $this->settings['_owc_setting_use_escape_element'] ?? false;

        return filter_var($setting, FILTER_VALIDATE_BOOLEAN);
    }

    public function useShowOn(): bool
    {
        $setting = $this->settings['_owc_setting_openpub_enable_show_on'] ?? false;

        return filter_var($setting, FILTER_VALIDATE_BOOLEAN);
    }

    public function expireAfter(): int
    {
        return $this->settings['_owc_setting_openpub_expired_auto_after_days'] ?? 0;
    }

    public function upgradeAdminNoticesDisabled(): bool
    {
        $setting = $this->settings['_owc_setting_openpub_disable_upgrade_admin_notice'] ?? false;

        return filter_var($setting, FILTER_VALIDATE_BOOLEAN);
    }

    public static function make(): self
    {
        $defaultSettings = [
            '_owc_setting_portal_url' => '',
            '_owc_setting_portal_openpub_item_slug' => '',
            '_owc_setting_use_portal_url' => 0,
            '_owc_setting_use_escape_element' => 0,
            '_owc_setting_openpub_enable_show_on' => 0,
            '_owc_setting_openpub_expired_auto_after_days' => 0,
            '_owc_setting_openpub_disable_upgrade_admin_notice' => 0
        ];

        return new static(wp_parse_args(get_option('_owc_openpub_base_settings'), $defaultSettings));
    }
}
