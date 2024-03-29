<?php

namespace OWC\OpenPub\Base\RestAPI\Controllers;

use OWC\OpenPub\Base\Repositories\Item;
use OWC\OpenPub\Base\RestAPI\ItemFields\FeaturedImageField;
use WP_Error;
use WP_Post;
use WP_Query;
use WP_REST_Request;

class ItemController extends BaseController
{
    /**
     * Get a list of all items, active and inactive.
     *
     * @throws \ReflectionException|\OWC\OpenPub\Base\Exceptions\PropertyNotExistsException
     */
    public function getItems(WP_REST_Request $request): array
    {
        $items = $this->itemQueryBuilder($request);

        return $this->response($items);
    }

    /**
     * @throws \ReflectionException|\OWC\OpenPub\Base\Exceptions\PropertyNotExistsException
     */
    public function getActiveItems(WP_REST_Request $request): array
    {
        $items = $this->itemQueryBuilder($request)
            ->query(Item::addExpirationParameters());

        return $this->response($items);
    }

    protected function itemQueryBuilder(WP_REST_Request $request = null): Item
    {
        $items = new Item();
        $items = $items->query(apply_filters('owc/openpub/rest-api/items/query', $this->getPaginatorParams($request)));

        if ($this->hasHighlightedParam($request)) {
            $items->query(Item::addHighlightedParameters($this->getHighlightedParam($request)));
        }

        if ($this->getTypeParam($request)) {
            $items->query(Item::addTypeParameter($this->getTypeParam($request)));
        }

        if ($this->showOnParamIsValid($request) && $this->plugin->settings->useShowOn()) {
            $items->query(Item::addShowOnParameter($request->get_param('source')));
        }

        if ($this->getAudienceParam($request)) {
            $items->query(Item::addAudienceParameters($this->getAudienceParam($request)));
        }

        return $items;
    }

    /**
     * Get an individual post item.
     *
     * @return array|WP_Error
     * @throws \OWC\OpenPub\Base\Exceptions\PropertyNotExistsException
     * @throws \ReflectionException
     */
    public function getItem(WP_REST_Request $request)
    {
        $id = (int) $request->get_param('id');

        $item = $this->singleItemQueryBuilder($request);
        $item = $item->find($id);

        if (! $item) {
            return new WP_Error('no_item_found', sprintf('Item with ID "%d" not found (anymore)', $id), [
                'status' => 404,
            ]);
        }

        $item['related'] = $this->addRelated($item, $request);

        return $item;
    }

    /**
     * Get an individual post item by slug.
     *
     * @return array|WP_Error
     */
    public function getItemBySlug(WP_REST_Request $request)
    {
        $slug = $request->get_param('slug');

        $item = $this->singleItemQueryBuilder($request);
        $item = $item->findBySlug($slug);

        if (! $item) {
            return new WP_Error('no_item_found', sprintf('Item with slug "%d" not found', $slug), [
                'status' => 404,
            ]);
        }

        $item['related'] = $this->addRelated($item, $request);

        return $item;
    }

    public function singleItemQueryBuilder(WP_REST_Request $request): Item
    {
        $item = (new Item())
            ->query(apply_filters('owc/openpub/rest-api/items/query/single', []));

        $preview = filter_var($request->get_param('draft-preview'), FILTER_VALIDATE_BOOLEAN);

        if (true === $preview && is_user_logged_in()) {
            $item->query(['post_status' => ['publish', 'draft', 'future']]);
        }

        return $item;
    }

    /**
     * Get related items
     */
    protected function addRelated(array $item, WP_REST_Request $request): array
    {
        $items = (new Item())
            ->query([
                'post__not_in'   => [$item['id']],
                'posts_per_page' => 10,
                'post_status'    => 'publish',
                'post_type'      => 'openpub-item',
            ])
            ->query(Item::addExpirationParameters());

        if ($this->getTypeParam($request)) {
            $items->query(Item::addTypeParameter($this->getTypeParam($request)));
        }

        if ($this->showOnParamIsValid($request) && $this->plugin->settings->useShowOn()) {
            $items->query(Item::addShowOnParameter($request->get_param('source')));
        }

        if ($this->getAudienceParam($request)) {
            $items->query(Item::addAudienceParameters($this->getAudienceParam($request)));
        }

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
            'image'         => $this->getImageUrl($post),
            'slug'          => $post->post_name,
        ];

        return $data;
    }

    public function getImageUrl(WP_Post $post): array
    {
        return (new FeaturedImageField($this->plugin))->create($post);
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

        if (! $this->validateBoolean($request->get_param('highlighted'))) {
            return false;
        };

        return true;
    }

    protected function getTypeParam(WP_REST_Request $request): string
    {
        $typeParam = $request->get_param('type');

        if (empty($typeParam)) {
            return '';
        }

        if (is_array($typeParam)) {
            /**
             * Implode to a string so the ItemRepository class can work with multiple params.
             * In the previous versions the $typeParam was always assumed to be a string.
             */
            $typeParam = implode(',', $typeParam);
        }

        return is_string($typeParam) ? $typeParam : '';
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
     */
    protected function showOnParamIsValid(WP_REST_Request $request): bool
    {
        if (empty($request->get_param('source'))) {
            return false;
        }

        if (! is_numeric($request->get_param('source'))) {
            return false;
        }

        return true;
    }

    protected function getAudienceParam(WP_REST_Request $request): string
    {
        $audienceParam = $request->get_param('audience');

        if (empty($audienceParam)) {
            return '';
        }

        if (is_array($audienceParam)) {
            // Implode to a string so the ItemRepository class can work with multiple params
            $audienceParam = implode(',', $audienceParam);
        }

        return is_string($audienceParam) ? $audienceParam : '';
    }
}
