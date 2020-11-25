<?php

namespace OWC\OpenPub\Base\Taxonomy;

use OWC\OpenPub\Base\Foundation\ServiceProvider;

class TaxonomyServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->plugin->make('loader')->addAction('init', $this, 'registerTaxonomies');
    }

    /**
     * Register custom taxonomies via extended_cpts
     */
    public function registerTaxonomies(): void
    {
        if (function_exists('register_extended_taxonomy')) {
            foreach ($this->plugin->make('config')->get('taxonomies', []) as $taxonomyName => $taxonomy) {
                \register_extended_taxonomy($taxonomyName, $taxonomy['object_types'], $taxonomy['args'], $taxonomy['names']);
            }
        }
    }
}
