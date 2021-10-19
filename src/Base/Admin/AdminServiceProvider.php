<?php

namespace OWC\OpenPub\Base\Admin;

use OWC\OpenPub\Base\Foundation\ServiceProvider;
use OWC\OpenPub\Base\Models\Item;

class AdminServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->plugin->loader->addFilter('preview_post_link', $this, 'filterPreviewLink', 10, 2);
        $this->plugin->loader->addFilter('post_type_link', $this, 'filterPostLink', 10, 2);
    }

    /**
     * Change the url for preview in the portal.
     */
    public function filterPostLink(string $link, \WP_Post $post): string
    {
        if ($post->post_type !== 'openpub-item') {
            return $link;
        }

        return Item::makeFrom($post)->getPortalURL();
    }

   /**
     * Change the url for preview of drafts in the portal.
     */
    public function filterPreviewLink(string $link, \WP_Post $post): string
    {
        if ($post->post_type !== 'openpub-item') {
            return $link;
        }

        return Item::makeFrom($post)->getPortalURL() . "?preview=true";
    }
}
