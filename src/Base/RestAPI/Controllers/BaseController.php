<?php

namespace OWC\OpenPub\Base\RestAPI\Controllers;

use OWC\OpenPub\Base\Foundation\Plugin;
use OWC\OpenPub\Base\Repositories\AbstractRepository;
use WP_Query;
use WP_REST_Request;

abstract class BaseController
{
    protected Plugin $plugin;

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
        return array_merge($this->getTaxonomyParams($request), [
            'posts_per_page' => $request->get_param('limit') ?: $limit,
            'paged'          => $request->get_param('page') ?: 0
        ]);
    }

    /**
     * Get the request params WP_Query turns into a tax_query for OpenPub taxonomies, e.g. ?openpub-type=slug.
     */
    protected function getTaxonomyParams(WP_REST_Request $request): array
    {
        return array_intersect_key($request->get_params(), $this->taxonomyArguments());
    }

    public function paginatorArguments(): array
    {
        return [
            'limit' => [
                'description' => __('Number of items per page, -1 for all items.', 'openpub-base'),
                'type'        => 'integer',
                'minimum'     => -1,
                'default'     => 10,
            ],
            'page' => [
                'description' => __('Page number.', 'openpub-base'),
                'type'        => 'integer',
                'minimum'     => 1,
                'default'     => 1,
            ],
        ];
    }

    /**
     * Route args for the taxonomy query vars getTaxonomyParams() passes to WP_Query.
     */
    public function taxonomyArguments(): array
    {
        $args = [];

        foreach (get_object_taxonomies('openpub-item', 'objects') as $taxonomy) {
            if (empty($taxonomy->query_var)) {
                continue;
            }

            // String first, so values reach WP_Query unsplit and keep its "a+b" (AND) syntax.
            $args[$taxonomy->query_var] = [
                'description' => sprintf(__('Filter on %s term slugs.', 'openpub-base'), $taxonomy->name),
                'type'        => ['string', 'array'],
                'items'       => ['type' => 'string'],
            ];
        }

        return $args;
    }
}
