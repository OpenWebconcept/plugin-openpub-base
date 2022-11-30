<?php

namespace OWC\OpenPub\Base\RestAPI\ItemFields;

use WP_Post;

class Yoast extends AbstractSEO
{
    protected function condition(): callable
    {
        return function () {
            return $this->isPluginActive('wordpress-seo/wp-seo.php')
                || $this->isPluginActive('wordpress-seo-premium/wp-seo-premium.php');
        };
    }

    /**
     * Create Yoast SEO API fields.
     */
    public function create(WP_Post $post): array
    {
        $seoMetaFields = $this->plugin->config->get('yoast_api.meta');

        if (! is_array($seoMetaFields) || empty($seoMetaFields)) {
            return [];
        }

        $relatedFields = $this->getRelatedMetaFields($seoMetaFields, $this->getPostMeta($post));

        return $this->mapFields($relatedFields);
    }
}
