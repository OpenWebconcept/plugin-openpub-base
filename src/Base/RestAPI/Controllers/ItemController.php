<?php

namespace OWC\OpenPub\Base\RestAPI\Controllers;

use OWC\OpenPub\Base\Repositories\Item;
use WP_Error;
use WP_Post;
use WP_Query;
use WP_REST_Request;

class ItemController extends BaseController
{
    /**
     * Get a list of all items, active and inactive.
     *
     * @param WP_REST_Request $request
     *
     * @return array
     * @throws \OWC\OpenPub\Base\Exceptions\PropertyNotExistsException
     * @throws \ReflectionException
     */
    public function getItems(WP_REST_Request $request)
    {
        $items = (new Item())
            ->query(apply_filters('owc/openpub/rest-api/items/query', $this->getPaginatorParams($request)));

        if ($this->hasHighlightedParam($request)) {
            $items->query(Item::addHighlightedParameters($this->getHighlightedParam($request)));
        }

        if ($this->showOnParamIsValid($request)) {
            $items->query(Item::addShowOnParameter($request->get_param('source')));
        }

        $data  = $items->all();
        $query = $items->getQuery();

        return $this->addPaginator($data, $query);
    }

    /**
     * Get a list of all active items.
     *
     * @param WP_REST_Request $request
     *
     * @return array
     * @throws \OWC\OpenPub\Base\Exceptions\PropertyNotExistsException
     * @throws \ReflectionException
     */
    public function getActiveItems(WP_REST_Request $request)
    {
        $items = (new Item())
            ->query(apply_filters('owc/openpub/rest-api/items/query', $this->getPaginatorParams($request)))
            ->query(Item::addExpirationParameters());

        if ($this->showOnParamIsValid($request)) {
            $items->query(Item::addShowOnParameter($request->get_param('source')));
        }

        $data  = $items->all();
        $query = $items->getQuery();

        return $this->addPaginator($data, $query);
    }

    /**
     * Get an individual post item.
     *
     * @param WP_REST_Request $request $request
     *
     * @return array|WP_Error
     * @throws \OWC\OpenPub\Base\Exceptions\PropertyNotExistsException
     * @throws \ReflectionException
     */
    public function getItem(WP_REST_Request $request)
    {
        $id = (int) $request->get_param('id');

        $item = (new Item)
            ->query(apply_filters('owc/openpub/rest-api/items/query/single', []))
            ->find($id);

        if (!$item) {
            return new WP_Error('no_item_found', sprintf('Item with ID "%d" not found (anymore)', $id), [
                'status' => 404,
            ]);
        }

        $item['related'] = $this->addRelated($item);

        return $item;
    }

    /**
     * Get an individual post item by slug.
     *
     * @param $request $request
     *
     * @return array|WP_Error
     */
    public function getItemBySlug(WP_REST_Request $request)
    {
        $slug = $request->get_param('slug');

        $item = (new Item)
            ->query(apply_filters('owc/openpub/rest-api/items/query/single', []))
            ->findBySlug($slug);

        if (!$item) {
            return new WP_Error('no_item_found', sprintf('Item with slug "%d" not found', $slug), [
                'status' => 404,
            ]);
        }

        $item['related'] = $this->addRelated($item);

        return $item;
    }

    /**
     * Get related items
     */
    protected function addRelated(array $item): array
    {
        $items = (new Item())
            ->query([
                'post__not_in'   => [$item['id']],
                'posts_per_page' => 10,
                'post_status'    => 'publish',
                'post_type'      => 'openpub-item',
            ])
            ->query(Item::addExpirationParameters());

        $query = new WP_Query($items->getQueryArgs());
        return array_map([$this, 'transform'], $query->posts);
    }

    /**
     * Transform a single WP_Post item into an array
     */
    public function transform(WP_Post $post): array
    {
        $data = [
            'id'            => $post->ID,
            'title'         => $post->post_title,
            'content'       => \apply_filters('the_content', $post->post_content),
            'excerpt'       => $post->post_excerpt,
            'date'          => $post->post_date,
            'thumbnail_url' => \get_the_post_thumbnail_url($post->ID),
            'slug'          => $post->post_name,
        ];

        return $data;
    }

    protected function getHighlightedParam(WP_REST_Request $request): bool
    {
        return filter_var($request->get_param('highlighted'), FILTER_VALIDATE_BOOLEAN);
    }

    protected function hasHighlightedParam(WP_REST_Request $request): bool
    {
        if (empty($request->get_param('highlighted'))) {
            return false;
        }

        if (!$this->validateBoolean($request->get_param('highlighted'))) {
            return false;
        };

        return true;
    }

    protected function validateBoolean(string $value): bool
    {
        $value = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if (null === $value) {
            return false;
        }

        return true;
    }

    /**
     * Validate if show on param is valid.
     * Param should be a numeric value.
     *
     * @param WP_REST_Request $request
     * @return boolean
     */
    protected function showOnParamIsValid(WP_REST_Request $request): bool
    {
        if (empty($request->get_param('source'))) {
            return false;
        }

        if (!is_numeric($request->get_param('source'))) {
            return false;
        }

        return true;
    }
}
