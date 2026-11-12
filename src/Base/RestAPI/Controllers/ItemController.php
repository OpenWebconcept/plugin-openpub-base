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

    /**
     * Route args for the items and items/active endpoints.
     */
    public function collectionArguments(): array
    {
        return array_merge($this->paginatorArguments(), $this->filterArguments(), [
            'highlighted' => [
                'description' => __('Only highlighted items (true) or only non-highlighted items (false).', 'openpub-base'),
                'type'        => 'boolean',
            ],
        ], $this->taxonomyArguments());
    }

    /**
     * Route args for the single item endpoints; the filters apply to the related items.
     */
    public function singleArguments(): array
    {
        return array_merge($this->filterArguments(), [
            'draft-preview' => [
                'description' => __('Include draft and scheduled items, for logged-in users.', 'openpub-base'),
                'type'        => 'boolean',
            ],
        ]);
    }

    protected function filterArguments(): array
    {
        return [
            'type' => [
                'description' => __('Filter on openpub-type term slugs.', 'openpub-base'),
                'type'        => 'array',
                'items'       => ['type' => 'string'],
            ],
            'audience' => [
                'description' => __('Filter on openpub-audience term slugs.', 'openpub-base'),
                'type'        => 'array',
                'items'       => ['type' => 'string'],
            ],
            'source' => [
                'description' => __('Filter on the blog ID the items are shown on, when "show on" is enabled.', 'openpub-base'),
                'type'        => 'integer',
            ],
        ];
    }

    protected function itemQueryBuilder(WP_REST_Request $request = null): Item
    {
        $items = new Item();
        $items = $items->query(apply_filters('owc/openpub/rest-api/items/query', $this->getPaginatorParams($request)));

        if (null !== $request->get_param('highlighted')) {
            $items->query(Item::addHighlightedParameters(rest_sanitize_boolean($request->get_param('highlighted'))));
        }

        if ($this->getTypeParam($request)) {
            $items->query(Item::addTypeParameter($this->getTypeParam($request)));
        }

        if ($request->get_param('source') && $this->plugin->settings->useShowOn()) {
            $items->query(Item::addShowOnParameter((string) $request->get_param('source')));
        }

        if ($this->getAudienceParam($request)) {
            $items->query(Item::addAudienceParameters($this->getAudienceParam($request)));
        }

        return apply_filters('owc/openpub/rest-api/items/query/parameters', $items, $request);
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
                'no_found_rows'  => true,
            ])
            ->query(Item::addExpirationParameters());

        $type = $this->getTypeParam($request);

        if (! $type && $this->plugin->settings->useRelatedItemsOwnType()) {
            $type = $this->getItemTypeSlugs($item);
        }

        if ($type) {
            $items->query(Item::addTypeParameter($type));
        }

        if ($request->get_param('source') && $this->plugin->settings->useShowOn()) {
            $items->query(Item::addShowOnParameter((string) $request->get_param('source')));
        }

        if ($this->getAudienceParam($request)) {
            $items->query(Item::addAudienceParameters($this->getAudienceParam($request)));
        }

        $items = apply_filters('owc/openpub/rest-api/items/query/parameters', $items, $request);

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

    /**
     * Get the type slugs already attached to an item, comma-separated.
     *
     * @since 3.9.0
     */
    protected function getItemTypeSlugs(array $item): string
    {
        $types = $item['taxonomies']['openpub-type'] ?? [];

        if (! is_array($types) || [] === $types) {
            return '';
        }

        return implode(',', array_column($types, 'slug'));
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

    /**
     * Validate if show on param is valid.
     * Param should be a numeric value.
     *
     * @deprecated 3.10.0 The source route arg is validated as an integer; kept for child plugins built against older versions.
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
