<?php

namespace OWC\OpenPub\Base\RestAPI\Controllers;

use OWC\OpenPub\Base\Foundation\Plugin;
use WP_Query;
use WP_REST_Request;

abstract class BaseController
{

    /**
     * Instance of the plugin.
     *
     * @var Plugin
     */
    protected $plugin;

    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Merges a paginator, based on a WP_Query, inside a data arary.
     *
     * @param array    $data
     * @param WP_Query $query
     *
     * @return array
     */
    protected function addPaginator(array $data, WP_Query $query): array
    {
        $page = $query->get('paged');
        $page = 0 == $page ? 1 : $page;

        return array_merge([
            'data' => $data
        ], [
            'pagination' => [
                'total_count'             => (int) $query->found_posts,
                'total_pages'             => $query->max_num_pages,
                'current_page'            => $page,
                'limit'                   => $query->get('posts_per_page'),
                'query_parameters'        => $query->query
            ]
        ]);
    }

    /**
     * Get the paginator query params for a given query.
     *
     * @param WP_REST_Request $request
     * @param int             $limit
     *
     * @return array
     */
    protected function getPaginatorParams(WP_REST_Request $request, int $limit = 10)
    {
        return array_merge($request->get_params(), [
            'posts_per_page' => $request->get_param('limit') ?: $limit,
            'paged'          => $request->get_param('page') ?: 0
        ]);
    }
}
