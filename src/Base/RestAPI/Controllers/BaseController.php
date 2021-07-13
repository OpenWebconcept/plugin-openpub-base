<?php

namespace OWC\OpenPub\Base\RestAPI\Controllers;

use OWC\OpenPub\Base\Foundation\Plugin;
use OWC\OpenPub\Base\Repositories\AbstractRepository;
use WP_Query;
use WP_REST_Request;

abstract class BaseController
{
    /** @var Plugin */
    protected $plugin;

    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
    }

    public function response(AbstractRepository $data): array
    {
        return $this->addPaginator($data->all(), $data->getQuery());
    }

    /**
     * Merges a paginator, based on a WP_Query, inside a data arary.
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
     */
    protected function getPaginatorParams(WP_REST_Request $request, int $limit = 10): array
    {
        return array_merge($request->get_params(), [
            'posts_per_page' => $request->get_param('limit') ?: $limit,
            'paged'          => $request->get_param('page') ?: 0
        ]);
    }
}
