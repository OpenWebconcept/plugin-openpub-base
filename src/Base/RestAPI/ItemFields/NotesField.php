<?php

namespace OWC\OpenPub\Base\RestAPI\ItemFields;

use OWC\OpenPub\Base\Support\CreatesFields;
use WP_Post;

class NotesField extends CreatesFields
{
    /**
     * Creates an array of connected posts.
     *
     * @param WP_Post $post
     *
     * @return string
     */
    public function create(WP_Post $post): string
    {
        return esc_attr($this->getNotes($post));
    }

    /**
     * Get notes of a post.
     *
     * @param WP_Post $post
     *
     * @return string
     */
    private function getNotes(WP_Post $post)
    {
        return get_post_meta($post->ID, '_owc_openpub_notes', true) ?: '';
    }
}
