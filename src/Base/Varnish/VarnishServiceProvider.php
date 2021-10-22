<?php

namespace OWC\OpenPub\Base\Varnish;

use OWC\OpenPub\Base\Foundation\ServiceProvider;

class VarnishServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->plugin->loader->addAction('save_post', $this, 'purgeVarnishCache', 10, 2);
    }

    public function purgeVarnishCache(int $postID, \WP_Post $post): void
    {
        if (! function_exists('curl_init')) {
            return;
        }

        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1/.*');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PURGE');
    
        $headers = [];
        $headers[] = 'Host: '. \parse_url(get_site_url(), PHP_URL_HOST);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        curl_exec($ch);
        curl_close($ch);
    }
}
