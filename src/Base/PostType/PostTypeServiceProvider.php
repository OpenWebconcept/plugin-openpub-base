<?php

namespace OWC\OpenPub\Base\PostType;

use OWC\OpenPub\Base\Foundation\ServiceProvider;
use WP_Query;

class PostTypeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->plugin->make('loader')->addAction('init', $this, 'registerPostTypes');
        $this->plugin->make('loader')->addAction('pre_get_posts', $this, 'orderByPublishedDate');
    }

    /**
     * Add default order.
     *
     * @param WP_Query $query
     * @return void
     */
    public function orderByPublishedDate(WP_Query $query): void
    {
        if (!is_admin()) {
            return;
        }

        if (!$query->is_main_query() || 'openpub-item' != $query->get('post_type')) {
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
    public function registerPostTypes()
    {
        if (function_exists('register_extended_post_type')) {
            foreach ($this->plugin->make('config')->get('posttypes', []) as $postTypeName => $postType) {
                \register_extended_post_type($postTypeName, $postType['args'], $postType['names']);
            }
        }
    }
}
