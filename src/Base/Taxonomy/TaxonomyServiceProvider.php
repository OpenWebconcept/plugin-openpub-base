<?php

namespace OWC\OpenPub\Base\Taxonomy;

use OWC\OpenPub\Base\Foundation\ServiceProvider;

class TaxonomyServiceProvider extends ServiceProvider
{

    /**
     * The array of taxonomies definitions from the config.
     */
    protected array $configTaxonomies = [];

    public function register(): void
    {
        $this->plugin->loader->addAction('init', $this, 'registerTaxonomies');

        if ($this->plugin->settings->useShowOn()) {
            $this->showOnFormFields();
        }
    }

    /**
     * Add elements to the taxonomy form.
     */
    protected function showOnFormFields()
    {
        $this->plugin->loader->addAction('openpub-show-on_add_form_fields', TaxonomyController::class, 'addShowOnExplanation');
    }

    /**
     * Register custom taxonomies via extended_cpts.
     */
    public function registerTaxonomies(): void
    {
        if (!function_exists('register_extended_taxonomy')) {
            return;
        }

        $this->configTaxonomies = apply_filters('owc/openpub-base/before-register-extended-taxonomies', $this->filterConfigTaxonomies());

        foreach ($this->configTaxonomies as $taxonomyName => $taxonomy) {
            // Examples of registering taxonomies: http://johnbillion.com/extended-cpts/
            register_extended_taxonomy($taxonomyName, $taxonomy['object_types'], $taxonomy['args'], $taxonomy['names']);
        }
    }

    /**
     * Filter taxonomies based on plugin settings.
     */
    protected function filterConfigTaxonomies(): array
    {
        if ($this->plugin->settings->useShowOn()) {
            return $this->plugin->config->get('taxonomies');
        }

        return array_filter($this->plugin->config->get('taxonomies'), function ($taxonomyKey) {
            return ('openpub-show-on' !== $taxonomyKey);
        }, ARRAY_FILTER_USE_KEY);
    }
}
