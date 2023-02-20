<?php

/**
 * Adds portal url field to the output.
 */

namespace OWC\OpenPub\Base\RestAPI\ItemFields;

use OWC\OpenPub\Base\Models\Item;
use OWC\OpenPub\Base\Support\CreatesFields;
use WP_Post;

class PortalURL extends CreatesFields
{
    /**
     * The condition for the creator.
     */
    protected function condition(): callable
    {
        return function () {
            return $this->plugin->settings->usePortalURL();
        };
    }

    /**
     * Create the portal url field for a given post.
     */
    public function create(WP_Post $post): string
    {
        $itemModel = Item::makeFrom($post);

        return $itemModel->getPortalURL();
    }
}
