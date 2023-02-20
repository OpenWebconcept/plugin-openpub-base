<?php

namespace OWC\OpenPub\Base\RestAPI\ItemFields;

use OWC\OpenPub\Base\Models\Item;
use OWC\OpenPub\Base\Support\CreatesFields;
use WP_Post;

/**
 * Adds escape element field to the output.
 */
class EscapeElementField extends CreatesFields
{
    /**
     * The condition for the creator.
     */
    protected function condition(): callable
    {
        return function () {
            return $this->plugin->settings->useEscapeElement();
        };
    }

    /**
     * Create the identifications field for a given post.
     */
    public function create(WP_Post $post): bool
    {
        $itemModel = new Item($post->to_array());

        return $itemModel->getEscapeElement();
    }
}
