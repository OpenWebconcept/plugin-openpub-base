<?php

namespace OWC\OpenPub\Base\RestAPI\ItemFields;

use OWC\OpenPub\Base\Support\CreatesFields;
use WP_Post;

class SynonymsField extends CreatesFields
{
    /**
     * Generate the synonyms field.
     *
     * @param WP_Post $post
     *
     * @return string
     */
    public function create(WP_Post $post): string
    {
        return esc_attr(strip_tags($this->getSynonyms($post)));
    }

    /**
     * Get synonyms of a post, if URL & title are present.
     *
     * @param WP_Post $post
     *
     * @return string
     */
    private function getSynonyms(WP_Post $post)
    {
        return get_post_meta($post->ID, '_owc_openpub_tags', true) ?: '';
    }
}
