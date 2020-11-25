<?php

/**
 * Adds portal url field to the output.
 */

namespace OWC\OpenPub\Base\RestAPI\ItemFields;

use WP_Post;
use OWC\OpenPub\Base\Models\Item;
use OWC\OpenPub\Base\Support\CreatesFields;

class PortalURL extends CreatesFields
{
    /**
     * The condition for the creator.
     *
     * @return callable
     */
    protected function condition(): callable
    {
        return function () {
            return $this->plugin->settings->usePortalURL();
        };
    }

    /**
     * Create the portal url field for a given post.
     *
     * @param WP_Post $post
     *
     * @return string
     */
    public function create(WP_Post $post): string
    {
        $itemModel = Item::makeFrom($post);

        return $itemModel->getPortalURL();
    }
}
