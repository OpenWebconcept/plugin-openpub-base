<?php

namespace OWC\OpenPub\Base\Metabox;

class MetaboxServiceProvider extends MetaboxBaseServiceProvider
{
    public function register()
    {
        $this->plugin->loader->addFilter('rwmb_meta_boxes', $this, 'registerMetaboxes', 10, 1);
    }

    /**
     * Register metaboxes.
     *
     * @param $rwmbMetaboxes
     *
     * @return array
     */
    public function registerMetaboxes($rwmbMetaboxes)
    {
        $configMetaboxes = $this->plugin->config->get('metaboxes');

        // Add metabox if plugin setting is checked.
        if ($this->plugin->settings->useEscapeElement()) {
            $configMetaboxes = $this->getEscapeElementMetabox($configMetaboxes);
        }

        // Add metabox if plugin setting is checked.
        if ($this->plugin->settings->useShowOn()) {
            $configMetaboxes = $this->getShowOnMetabox($configMetaboxes);
        }

        // Add default value when setting expireAfter is defined and has a bigger value than zero.
        if ($this->plugin->settings->expireAfter() > 0) {
            $configMetaboxes = $this->addExpirationDefaultValue($configMetaboxes);
        }

        $metaboxes = [];

        foreach ($configMetaboxes as $metabox) {
            $metaboxes[] = $this->processMetabox($metabox);
        }

        return array_merge($rwmbMetaboxes, apply_filters("owc/openpub/base/before-register-metaboxes", $metaboxes));
    }

    protected function addExpirationDefaultValue(array $configMetaboxes): array
    {
        $configMetaboxes['base']['fields']['general']['expiration']['std'] = (new \DateTime('now', new \DateTimeZone('Europe/Amsterdam')))->modify('+' . $this->plugin->settings->expireAfter() . ' days')->format('Y-m-d H:i');
        $configMetaboxes['base']['fields']['general']['expiration']['desc'] = __('Items with an expiration date will be excluded from the search results and on news overview page from this date forward.', 'openpub-base');

        return $configMetaboxes;
    }

    protected function getEscapeElementMetabox(array $configMetaboxes): array
    {
        return array_merge($configMetaboxes, $this->plugin->config->get('escape_element_metabox'));
    }

    protected function getShowOnMetabox(array $configMetaboxes): array
    {
        return array_merge($configMetaboxes, $this->plugin->config->get('show_on_metabox'));
    }
}
