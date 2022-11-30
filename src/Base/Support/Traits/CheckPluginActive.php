<?php

namespace OWC\OpenPub\Base\Support\Traits;

trait CheckPluginActive
{
    /**
     * @param string $file // example: 'pdc-internal-products/pdc-internal-products.php'
     */
    public function isPluginActive(string $file): bool
    {
        if (! function_exists('is_plugin_active')) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        return is_plugin_active($file);
    }

    public function isPluginSeoPressActive(): bool
    {
        return $this->isPluginActive('wp-seopress/seopress.php');
    }

    public function isPluginYoastSeoActive(): bool
    {
        return $this->isPluginActive('wordpress-seo/wp-seo.php');
    }
}
