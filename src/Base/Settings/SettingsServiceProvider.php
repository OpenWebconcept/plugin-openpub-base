<?php

namespace OWC\OpenPub\Base\Settings;

use OWC\OpenPub\Base\Metabox\MetaboxBaseServiceProvider;

class SettingsServiceProvider extends MetaboxBaseServiceProvider
{
    const PREFIX = '_owc_';

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->plugin->loader->addFilter('mb_settings_pages', $this, 'registerSettingsPage', 10, 1);
        $this->plugin->loader->addFilter('rwmb_meta_boxes', $this, 'registerSettings', 10, 1);
    }

    /**
     * @param array $rwmbSettingsPages
     *
     * @return array
     */
    public function registerSettingsPage(array $rwmbSettingsPages): array
    {
        $settingsPages = $this->plugin->config->get('settings_pages');

        return array_merge($rwmbSettingsPages, $settingsPages);
    }

    /**
     * Register metaboxes for settings page
     *
     * @param array $rwmbMetaboxes
     *
     * @return array
     */
    public function registerSettings(array $rwmbMetaboxes): array
    {
        $configMetaboxes = $this->plugin->config->get('settings');
        $metaboxes       = [];

        foreach ($configMetaboxes as $metabox) {
            $metaboxes[] = $this->processMetabox($metabox);
        }

        return array_merge($rwmbMetaboxes, apply_filters("owc/openpub/base/before-register-settings", $metaboxes));
    }
}
