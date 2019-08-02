<?php

namespace OWC\OpenPub\Base\RestAPI\ItemFields;

use OWC\OpenPub\Base\Support\CreatesFields;
use WP_Post;

class DownloadsField extends CreatesFields
{

    /**
     * Get the downloads associated to the post.
     *
     * @param WP_Post $post
     *
     * @return array
     */
    public function create(WP_Post $post): array
    {
        return array_map(function ($download) {
            return [
                'title' => esc_attr(strip_tags($download['openpub_downloads_title'])),
                'url'   => esc_url($download['openpub_downloads_url'])
            ];
        }, $this->getDownloads($post));
    }

    /**
     * Get downloads of a post, if URL & title are present.
     *
     * @param WP_Post $post
     *
     * @return array
     */
    private function getDownloads(WP_Post $post)
    {
        return array_filter(get_post_meta($post->ID, '_owc_openpub_downloads_group', true) ?: [], function ($download) {
            return ! empty($download['openpub_downloads_url']) && ! empty($download['openpub_downloads_title']);
        });
    }
}
