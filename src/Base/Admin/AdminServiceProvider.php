<?php

namespace OWC\OpenPub\Base\Admin;

use OWC\OpenPub\Base\Foundation\ServiceProvider;
use OWC\OpenPub\Base\Models\Item;

class AdminServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->plugin->loader->addFilter('preview_post_link', $this, 'filterPostLink', 10, 2);
        $this->plugin->loader->addFilter('post_type_link', $this, 'filterPostLink', 10, 2);
    }

    /**
     * Changes the url user for live preview to the portal url.
     */
    public function filterPostLink($link, \WP_Post $post): string
    {
        if ($post->post_type !== 'openpub-item') {
            return $link;
        }

        $itemModel = Item::makeFrom($post);

        return $itemModel->getPortalURL();
    }
}
