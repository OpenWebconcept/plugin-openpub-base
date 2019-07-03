<?php

namespace OWC\OpenPub\Base\RestAPI\Controllers;

use OWC\OpenPub\Base\Models\Item;
use WP_Error;
use WP_Post;
use WP_Query;
use WP_REST_Request;

class ItemController extends BaseController
{

    /**
     * Get a list of all items.
     *
     * @param WP_REST_Request $request
     *
     * @return array
     * @throws \OWC\OpenPub\Base\Exceptions\PropertyNotExistsException
     * @throws \ReflectionException
     */
    public function getItems(WP_REST_Request $request)
    {
        $items = ( new Item() )
            ->query(apply_filters('owc/openpub/rest-api/items/query', $this->getPaginatorParams($request)));

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
     * Get related items.
     *
     * @param array $item
     *
     * @return array
     */
    protected function addRelated($item)
    {
        $items = (new Item())
            ->query([
                'post__not_in'   => [$item['id']],
                'posts_per_page' => 10,
                'post_status'    => 'publish',
                'post_type'      => 'openpub-item',
            ]);
        $query = new WP_Query($items->getQueryArgs());
        return array_map([$this, 'transform'], $query->posts);
    }

    /**
     * Transform a single WP_Post item.
     *
     * @param WP_Post $post
     *
     * @return array
     */
    public function transform(WP_Post $post)
    {
        $data = [
            'id'      => $post->ID,
            'title'   => $post->post_title,
            'content' => apply_filters('the_content', $post->post_content),
            'excerpt' => $post->post_excerpt,
            'date'    => $post->post_date,
        ];

        return $data;
    }
}
