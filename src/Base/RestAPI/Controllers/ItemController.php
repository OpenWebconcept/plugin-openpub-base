<?php

namespace OWC\OpenPub\Base\RestAPI\Controllers;

use OWC\OpenPub\Base\Models\Item;
use WP_Error;
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
        $id = (int)$request->get_param('id');

        $item = ( new Item )
            ->query(apply_filters('owc/openpub/rest-api/items/query/single', []))
            ->find($id);

        if ( ! $item ) {
            return new WP_Error('no_item_found', sprintf('Item with ID "%d" not found (anymore)', $id), [
                'status' => 404
            ]);
        }

        return $item;
    }

}