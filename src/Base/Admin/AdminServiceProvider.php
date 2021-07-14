<?php

namespace OWC\OpenPub\Base\Admin;

use OWC\OpenPub\Base\Foundation\ServiceProvider;
use OWC\OpenPub\Base\Models\Item;

class AdminServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->plugin->loader->addAction('preview_post_link', $this, 'filterPostLink', 10, 10, 2);
        $this->plugin->loader->addAction('rest_prepare_pdc-item', $this, 'restPrepareResponseLink', 10, 2);
    }

    /**
     * Changes the url user for live preview to the portal url.
     * Works in the old editor (not gutenberg)
     */
    public function filterPostLink($link, \WP_Post $post): string
    {
        $itemModel = Item::makeFrom($post);
        $url       = $itemModel->getPortalURL();
        return $url . "?preview=true";
    }

    /**
     * Changes the url used for live preview to the portal url.
     * Works in the gutenberg editor.
     */
    public function restPrepareResponseLink(\WP_REST_Response $response, \WP_Post $post): \WP_REST_Response
    {
        $itemModel              = Item::makeFrom($post);
        $response->data['link'] = $itemModel->getPortalURL() ?? '';
        
        return $response;
    }
}
