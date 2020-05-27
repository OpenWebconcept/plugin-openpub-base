<?php

namespace OWC\OpenPub\Base\Taxonomy;

use OWC\OpenPub\Base\Foundation\ServiceProvider;

class TaxonomyServiceProvider extends ServiceProvider
{

    /**
     * the array of taxonomies definitions from the config
     *
     * @var array
     */
    protected $configTaxonomies = [];

    /**
     * @return void
     */
    public function register(): void
    {
        $this->plugin->loader->addAction('init', $this, 'registerTaxonomies');
    }

    /**
     * Register custom taxonomies via extended_cpts
     *
     * @return void
     */
    public function registerTaxonomies(): void
    {
        if (function_exists('register_extended_taxonomy')) {
            $this->configTaxonomies = $this->plugin->config->get('taxonomies');
            foreach ($this->configTaxonomies as $taxonomyName => $taxonomy) {
                // Examples of registering taxonomies: http://johnbillion.com/extended-cpts/
                register_extended_taxonomy($taxonomyName, $taxonomy['object_types'], $taxonomy['args'], $taxonomy['names']);
            }
        }
    }
}
