<?php

namespace OWC\OpenPub\Base\PostType;

use OWC\OpenPub\Base\Foundation\ServiceProvider;

class PostTypeServiceProvider extends ServiceProvider
{

    /**
     * The array of posttype definitions from the config
     *
     * @var array
     */
    protected $configPostTypes = [];

    public function register()
    {
        $this->plugin->loader->addAction('init', $this, 'registerPostTypes');
    }

    /**
     * register custom posttypes.
     */
    public function registerPostTypes()
    {

        if (function_exists('register_extended_post_type')) {
            $this->configPostTypes = $this->plugin->config->get('posttypes');
            foreach ($this->configPostTypes as $postTypeName => $postType) {
                // Examples of registering post types: http://johnbillion.com/extended-cpts/
                register_extended_post_type($postTypeName, $postType['args'], $postType['names']);
            }
        }
    }
}
