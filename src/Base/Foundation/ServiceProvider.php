<?php

namespace OWC\OpenPub\Base\Foundation;

use OWC\OpenPub\Base\Settings\SettingsPageOptions;

/**
 * Provider which handles the registration of the plugin.
 */
abstract class ServiceProvider
{

    /**
     * Instance of the plugin.
     *
     * @var \OWC\OpenPub\Base\Foundation\Plugin
     */
    protected $plugin;

    /**
     * Construction of the service provider.
     *
     * @param Plugin $plugin
     *
     * @return void
     */
    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
        $this->plugin->settings = SettingsPageOptions::make();
    }

    /**
     * Register the service provider.
     */
    abstract public function register();
}
