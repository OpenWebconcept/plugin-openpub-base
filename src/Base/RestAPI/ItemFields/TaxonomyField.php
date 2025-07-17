<?php

namespace OWC\OpenPub\Base\RestAPI\ItemFields;

use OWC\OpenPub\Base\Support\CreatesFields;
use WP_Post;

class TaxonomyField extends CreatesFields
{
    /**
     * Create an additional field on an array.
     */
    public function create(WP_Post $post): array
    {
    	$result = [];

	    $taxonomies = apply_filters('owc/openpub-base/before-register-extended-taxonomies', $this->plugin->config->get('taxonomies'));

        if (! is_array($taxonomies) || 1 > count($taxonomies)) {
          return $result;
	    }

	    $taxonomiesKeys = array_unique(array_keys($taxonomies));

        foreach ($taxonomiesKeys as $taxonomy) {
            $result[$taxonomy] = $this->getTerms($post->ID, $taxonomy);
        }

        return $result;
    }

    /**
     * Get terms of a taxonomy to which the post is connected.
     */
    private function getTerms(int $postID, string $taxonomy): array
    {
        $terms = \wp_get_post_terms($postID, $taxonomy);

        if (\is_wp_error($terms)) {
            return [];
        }

        return array_map(function ($term) {
            return [
                'id'   => $term->term_id,
                'name' => $term->name,
                'slug' => $term->slug
            ];
        }, $terms);
    }
}
