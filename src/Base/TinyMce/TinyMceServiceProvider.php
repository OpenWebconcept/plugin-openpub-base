<?php

namespace OWC\OpenPub\Base\TinyMce;

use OWC\OpenPub\Base\Foundation\ServiceProvider;

class TinyMceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->plugin->loader->addFilter('mce_external_plugins', $this, 'addSelectionGuard');
    }

    public function addSelectionGuard(array $plugins): array
    {
        // A TinyMCE plugin rides along in tinyMCEPreInit.mceInit, so Meta Box clones get it too. See the script.
        $plugins['owcselectionguard'] = $this->plugin->getPluginUrl() . '/js/editor-selection-guard.js?ver=' . $this->plugin->getVersion();

        return $plugins;
    }
}
