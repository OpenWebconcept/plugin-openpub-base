<?php

namespace OWC\OpenPub\Base\Settings;

use OWC\OpenPub\Base\Foundation\ServiceProvider;
use OWC\OpenPub\Base\Metabox\MetaboxBaseServiceProvider;

class SettingsServiceProvider extends MetaboxBaseServiceProvider
{

    public function register()
    {
        $this->plugin->loader->addFilter('mb_settings_pages', $this, 'registerSettingsPage', 10, 1);
        $this->plugin->loader->addFilter('rwmb_meta_boxes', $this, 'registerSettings', 10, 1);
        $this->plugin->loader->addAction('admin_init', $this, 'getSettingsOption');
    }

    /**
     *
     */
    public function registerSettingsPage($rwmbSettingsPages)
    {

        $settingsPages = $this->plugin->config->get('settings_pages');

        return array_merge($rwmbSettingsPages, $settingsPages);
    }

    /**
     * register metaboxes for settings page
     *
     * @param $rwmbMetaboxes
     *
     * @return array
     */
    public function registerSettings($rwmbMetaboxes)
    {
        $configMetaboxes = $this->plugin->config->get('settings');
        $metaboxes = [];

        foreach ($configMetaboxes as $metabox) {

            $metaboxes[] = $this->processMetabox($metabox);
        }

        return array_merge($rwmbMetaboxes, apply_filters("owc/openpub-base/before-register-settings", $metaboxes));
    }

    public function getSettingsOption()
    {
        //TODO implement better way of retrieving settings (used in InterfaceServiceProvider)
        $defaultSettings = [
            '_owc_setting_portal_url'           => '',
            '_owc_setting_portal_openpub_item_slug' => ''
        ];

        $this->plugin->settings = wp_parse_args(get_option(self::PREFIX.'openpub_base_settings'), $defaultSettings);
    }
}