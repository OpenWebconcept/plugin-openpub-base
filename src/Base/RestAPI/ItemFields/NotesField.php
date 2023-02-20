<?php

namespace OWC\OpenPub\Base\RestAPI\ItemFields;

use OWC\OpenPub\Base\Support\CreatesFields;
use WP_Post;

class NotesField extends CreatesFields
{
    public function create(WP_Post $post): string
    {
        return esc_attr($this->getNotes($post));
    }

    /**
     * Get notes of a post.
     */
    private function getNotes(WP_Post $post): string
    {
        return \get_post_meta($post->ID, '_owc_openpub_notes', true) ?: '';
    }
}
