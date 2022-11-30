<?php

namespace OWC\OpenPub\Base\RestAPI\ItemFields;

use OWC\OpenPub\Base\Support\CreatesFields;
use OWC\OpenPub\Base\Support\Traits\CheckPluginActive;
use WP_Post;

abstract class AbstractSEO extends CreatesFields
{
    use CheckPluginActive;

    /**
     * Get all meta fields of post.
     */
    protected function getPostMeta(WP_Post $post): array
    {
        $postMeta = \get_post_meta($post->ID, '', true);

        return is_array($postMeta) && ! empty($postMeta) ? $postMeta : [];
    }

    protected function getRelatedMetaFields(array $seoMetaFields, array $postmeta): array
    {
        if (empty($seoMetaFields) || empty($postmeta)) {
            return [];
        }

        return array_filter($postmeta, function ($item, $key) use ($seoMetaFields) {
            return in_array($key, $seoMetaFields);
        }, ARRAY_FILTER_USE_BOTH);
    }

    public function mapFields(array $relatedFields): array
    {
        if (empty($relatedFields)) {
            return [];
        }

        $mapped = array_map(function ($field) {
            return ! empty($field[0]) ? $field[0] : null;
        }, $relatedFields);

        return array_filter($mapped);
    }
}
