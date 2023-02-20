<?php

namespace OWC\OpenPub\Base\RestAPI\ItemFields;

use OWC\OpenPub\Base\Support\CreatesFields;
use WP_Post;

class HighlightedItemField extends CreatesFields
{
    /**
     * Generate the highlighted field.
     */
    public function create(WP_Post $post): bool
    {
        return $this->getHighlightedItem($post);
    }

    /**
     * Get option if post is highlighted.
     */
    private function getHighlightedItem(WP_Post $post): bool
    {
        return (bool) \get_post_meta($post->ID, '_owc_openpub_highlighted_item', true) ?: false;
    }
}
