<?php

namespace OWC\OpenPub\Base\RestAPI\ItemFields;

use WP_Post;

class SeoPress extends AbstractSEO
{
    protected function condition(): callable
    {
        return function () {
            return $this->isPluginActive('wp-seopress/seopress.php');
        };
    }

    /**
     * Create SeoPress API fields.
     */
    public function create(WP_Post $post): array
    {
        $seoMetaFields = $this->plugin->config->get('seopress_api.meta');

        if (! is_array($seoMetaFields) || empty($seoMetaFields)) {
            return [];
        }

        $relatedFields = $this->getRelatedMetaFields($seoMetaFields, $this->getPostMeta($post));

        return $this->mapFields($relatedFields);
    }
}
