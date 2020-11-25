<?php

namespace OWC\OpenPub\Base\ElasticPress;

use OWC\OpenPub\Base\Foundation\ServiceProvider;
use OWC\OpenPub\Base\Models\Item;

class ElasticPressServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register(): void
    {
        if (!\is_plugin_active('elasticpress/elasticpress.php')) {
            return;
        }
        $elasticPress = new ElasticPress($this->plugin->make('config'), new Item);
        $this->plugin->make('loader')->addAction('init', $elasticPress, 'setSettings', 10, 1);
        $this->plugin->make('loader')->addAction('init', $elasticPress, 'init', 10, 1);
        $this->plugin->make('loader')->addFilter('ep_post_mapping', $elasticPress, 'addMappings', 10, 1);
    }
}
