<?php

namespace OWC\OpenPub\Base\RestAPI\ItemFields;

use OWC\OpenPub\Base\Support\CreatesFields;
use WP_Post;

class LinksField extends CreatesFields
{

    /**
     * Generate the links field.
     *
     * @param WP_Post $post
     *
     * @return array
     */
    public function create(WP_Post $post): array
    {
        return array_map(function ($link) {
            return [
                'title' => esc_attr(strip_tags($link['openpub_links_title'])),
                'url'   => esc_url($link['openpub_links_url'])
            ];
        }, $this->getLinks($post));
    }

    /**
     * Get links of a post, if URL & title are present.
     *
     * @param WP_Post $post
     *
     * @return array
     */
    private function getLinks(WP_Post $post)
    {
        return array_filter(get_post_meta($post->ID, '_owc_openpub_links_group', true) ?: [], function ($link) {
            return ! empty($link['openpub_links_url']) && ! empty($link['openpub_links_title']);
        });
    }
}
