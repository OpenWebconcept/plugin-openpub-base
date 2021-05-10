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
        $configMetaboxes  = $this->plugin->config->get('metaboxes');

        // add metabox if plugin setting is checked.
        if ($this->plugin->settings->useEscapeElement()) {
            $configMetaboxes = $this->getEscapeElementMetabox($configMetaboxes);
        }

        // add metabox if plugin setting is checked.
        if ($this->plugin->settings->useShowOn()) {
            $configMetaboxes = $this->getShowOnMetabox($configMetaboxes);
        }

        $metaboxes = [];

        foreach ($configMetaboxes as $metabox) {
            $metaboxes[] = $this->processMetabox($metabox);
        }

        return array_merge($rwmbMetaboxes, apply_filters("owc/openpub/base/before-register-metaboxes", $metaboxes));
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
