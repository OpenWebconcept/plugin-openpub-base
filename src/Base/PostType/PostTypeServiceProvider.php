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
        $this->plugin->loader->addAction('wp_insert_post_data', $this, 'fillPostName', 10, 1);
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
        if (function_exists('register_extended_post_type')) {
            $this->configPostTypes = $this->plugin->config->get('posttypes');
            foreach ($this->configPostTypes as $postTypeName => $postType) {
                // Examples of registering post types: http://johnbillion.com/extended-cpts/
                register_extended_post_type($postTypeName, $postType['args'], $postType['names']);
            }
        }
    }

    /**
     * Fill the post_name when item is not published.
     * When previewing an openpub-item the post_name needs to be present.
     */
    public function fillPostName(array $post = []): array
    {
        if ($post['post_type'] !== 'openpub-item' || $post['post_status'] === 'publish') {
            return $post;
        }

        $post['post_name'] = \sanitize_title($post['post_title']);

        return $post;
    }
}
