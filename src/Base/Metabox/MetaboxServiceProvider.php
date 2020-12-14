<?php

namespace OWC\OpenPub\Base\Metabox;

class MetaboxServiceProvider extends MetaboxBaseServiceProvider
{
    public function register(): void
    {
        $this->plugin->loader->addFilter('rwmb_meta_boxes', $this, 'registerMetaboxes', 10, 1);
    }

    /**
     * Register metaboxes.
     *
     * @param array $rwmbMetaboxes
     * @return array
     */
    public function registerMetaboxes(array $rwmbMetaboxes): array
    {
        $configMetaboxes = $this->plugin->config->get('metaboxes');
        $metaboxes       = [];

        foreach ($configMetaboxes as $metabox) {
            $metaboxes[] = $this->processMetabox($metabox);
        }

        return array_merge($rwmbMetaboxes, apply_filters("owc/openpub/base/before-register-metaboxes", $metaboxes));
    }
}
