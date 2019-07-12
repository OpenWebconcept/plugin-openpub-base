<?php
/**
 * Provider which handles the registration of the plugin.
 */

namespace OWC\OpenPub\Base\Foundation;

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
    }

    /**
     * Register the service provider.
     */
    abstract public function register();
}
