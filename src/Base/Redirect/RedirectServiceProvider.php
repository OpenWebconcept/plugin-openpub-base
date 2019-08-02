<?php

namespace OWC\OpenPub\Base\Redirect;

use OWC\OpenPub\Base\Foundation\ServiceProvider;

class RedirectServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->plugin->loader->addAction('template_redirect', $this, 'redirectToMarketingSite', 10);
    }

    /**
     * Redirect to marketing site.
     */
    public function redirectToMarketingSite()
    {
        if (is_admin() || wp_doing_ajax() || is_feed() || WP_DEBUG) {
            return;
        }

        if (wp_redirect('https://www.openwebconcept.nl/')) {
            exit();
        }
    }
}
