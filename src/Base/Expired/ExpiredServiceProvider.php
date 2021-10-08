<?php

namespace OWC\OpenPub\Base\Expired;

use OWC\OpenPub\Base\Foundation\ServiceProvider;

class ExpiredServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->plugin->loader->addAction('update_option__owc_openpub_base_settings', new Expired(), 'expirationOptionChange', 10, 2);
    }
}
