<?php

namespace OWC\OpenPub\Base\ElasticPress;

use OWC\OpenPub\Base\Foundation\ServiceProvider;
use OWC\OpenPub\Base\Repositories\Item;

class ElasticPressServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     * @throws Exception
     */
    public function register()
    {
        $elasticPress = new ElasticPress($this->plugin->config, new Item);
        $this->plugin->loader->addAction('init', $elasticPress, 'setSettings', 10, 1);
        $this->plugin->loader->addAction('init', $elasticPress, 'init', 10, 1);
        $this->plugin->loader->addFilter('ep_post_mapping', $elasticPress, 'addMappings', 10, 1);
    }
}
