<?php

namespace OWC\OpenPub\Base\Admin\Settings;

use OWC\OpenPub\Base\Metabox\MetaboxBaseServiceProvider;

class SettingsServiceProvider extends MetaboxBaseServiceProvider
{
    const PREFIX = '_owc_';

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->plugin->make('loader')->addFilter('mb_settings_pages', $this, 'registerSettingsPage', 10, 1);
        $this->plugin->make('loader')->addFilter('rwmb_meta_boxes', $this, 'registerSettings', 10, 1);
    }

    public function registerSettingsPage(array $rwmbSettingsPages): array
    {
        $settingsPages = $this->plugin->make('config')->get('settings_pages');

        return array_merge($rwmbSettingsPages, $settingsPages);
    }

    /**
     * Register metaboxes for settings page
     */
    public function registerSettings(array $rwmbMetaboxes): array
    {
        $configMetaboxes = $this->plugin->make('config')->get('settings');
        $metaboxes       = [];

        foreach ($configMetaboxes as $metabox) {
            $metaboxes[] = $this->processMetabox($metabox);
        }

        return array_merge($rwmbMetaboxes, apply_filters("owc/openpub/base/before-register-settings", $metaboxes));
    }
}
