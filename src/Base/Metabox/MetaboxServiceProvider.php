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
        $metaboxes       = [];

        foreach ($configMetaboxes as $metabox) {
            $metaboxes[] = $this->processMetabox($metabox);
        }

        return array_merge($rwmbMetaboxes, apply_filters("owc/openpub/base/before-register-metaboxes", $metaboxes));
    }
}
