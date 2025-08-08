<?php

namespace OWC\OpenPub\Base\PostType;

use OWC\OpenPub\Base\Foundation\ServiceProvider;
use WP_Query;

class PostTypeServiceProvider extends ServiceProvider
{
    /**
     * The array of posttype definitions from the config
     */
    protected array $configPostTypes = [];

    public function register()
    {
        $this->plugin->loader->addAction('init', $this, 'registerPostTypes');
        $this->plugin->loader->addAction('pre_get_posts', $this, 'orderByPublishedDate');
        $this->plugin->loader->addAction('wp_insert_post_data', $this, 'fillPostName', 10, 4);
    }

    /**
     * Add default order.
     */
    public function orderByPublishedDate(WP_Query $query): void
    {
        if (! is_admin()) {
            return;
        }

        if (! $query->is_main_query() || 'openpub-item' != $query->get('post_type')) {
            return;
        }

        if (isset($_GET['orderby'])) {
            return;
        }

        $query->set('orderby', 'post_date');
        $query->set('order', 'DESC');
    }

    /**
     * Register custom posttypes.
     */
    public function registerPostTypes(): void
    {
		if (!function_exists('register_extended_post_type')) {
			return;
		}

		$this->configPostTypes = apply_filters('owc/openpub-base/before-register-posttypes', $this->plugin->config->get('posttypes'));

		foreach ($this->configPostTypes as $postTypeName => $postType) {
			// Examples of registering post types: http://johnbillion.com/extended-cpts/
			register_extended_post_type($postTypeName, $postType['args'], $postType['names']);
		}
    }

    /**
     * Always fill the post_name when empty and a post is not published.
     * When previewing an openpub-item the post_name needs to be present, which is set by default when the post is published.
     */
    public function fillPostName(array $post, array $postarr, array $unsanitizedPostarr, bool $update): array
    {
        if ('openpub-item' !== $post['post_type'] || empty($postarr['ID']) || 'publish' === $post['post_status']) {
            return $post;
        }

        if (! empty($postarr['post_name'])) {
            return $post;
        }

        $post['post_name'] = \sanitize_title($post['post_title']);

        return $post;
    }
}
