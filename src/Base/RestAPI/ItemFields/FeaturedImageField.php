<?php

namespace OWC\OpenPub\Base\RestAPI\ItemFields;

use OWC\OpenPub\Base\Support\CreatesFields;
use WP_Post;

class FeaturedImageField extends CreatesFields
{

    /**
     * Gets the featured image of a post.
     *
     * @param WP_Post $post
     *
     * @return array
     */
    public function create(WP_Post $post): array
    {
        if (! has_post_thumbnail($post->ID)) {
            return [];
        }

        $id         = get_post_thumbnail_id($post->ID);
        $attachment = get_post($id);
        $imageSize  = 'large';

        $result = [];

        $result['title']       = $attachment->post_title;
        $result['description'] = $attachment->post_content;
        $result['caption']     = $attachment->post_excerpt;
        $result['alt']         = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);

        $meta = $this->getAttachmentMeta($id);

        $result['rendered'] = wp_get_attachment_image($id, $imageSize);
        $result['sizes']    = wp_get_attachment_image_sizes($id, $imageSize, $meta);
        $result['srcset']   = wp_get_attachment_image_srcset($id, $imageSize, $meta);
        $result['meta']     = $meta;

        return $result;
    }

    /**
     * Get meta data of an attachment.
     *
     * @param $id
     *
     * @return array
     */
    private function getAttachmentMeta($id): array
    {
        $meta = wp_get_attachment_metadata($id, false);

        if (empty($meta['sizes'])) {
            return [];
        }

        $meta['sizes']['full']['file'] = basename(get_attached_file($id));
        $meta['sizes']['full']['width'] = $meta['width'];
        $meta['sizes']['full']['height'] = $meta['height'];
        $meta['sizes']['full']['mime-type'] = get_post_mime_type($id);

        foreach (array_keys($meta['sizes']) as $size) {
            $src                              = wp_get_attachment_image_src($id, $size);
            $meta['sizes'][$size]['url']      = $src[0];
            $meta['sizes'][$size]['rendered'] = wp_get_attachment_image($id, $size);
        }

        unset($meta['image_meta']);

        return $meta;
    }
}
