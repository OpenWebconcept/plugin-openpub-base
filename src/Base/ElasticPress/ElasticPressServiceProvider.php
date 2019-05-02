<?php

namespace OWC\OpenPub\Base\ElasticPress;

use Exception;
use OWC\OpenPub\Base\Foundation\ServiceProvider;
use OWC\OpenPub\Base\Models\Item;

class ElasticPressServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     * @throws Exception
     */
    public function register()
    {
        if ( ! function_exists('is_plugin_active')) {
            require_once(ABSPATH.'wp-admin/includes/plugin.php');
        }
        if (! is_plugin_active('elasticpress/elasticpress.php')) {
            throw new Exception('Plugin ElasticPress should be installed and active to run this plugin');
        }

        $elasticPress = new ElasticPress($this->plugin->config, new Item);
        $this->plugin->loader->addAction('init', $elasticPress, 'setSettings', 10, 1);
        $this->plugin->loader->addAction('init', $elasticPress, 'init', 10, 1);
    }

    /**
     * Register the service provider
     * @throws Exception
     */
    public function boot()
    {
    }
}
