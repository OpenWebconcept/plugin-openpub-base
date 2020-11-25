<?php

namespace OWC\OpenPub\Base\RestAPI\Controllers;

use OWC\OpenPub\Base\Repositories\Search;
use WP_REST_Request;

class SearchController extends ItemController
{

    /**
     * Search all items.
     *
     * @param WP_REST_Request $request
     *
     * @return array
     * @throws \OWC\OpenPub\Base\Exceptions\PropertyNotExistsException
     * @throws \ReflectionException
     */
    public function search(WP_REST_Request $request)
    {
        $search = (new Search($request))
            ->query(['post_type' => 'any'])
            ->query(apply_filters('owc/openpub/rest-api/search/query', $this->getPaginatorParams($request)));

        $data  = $search->all();
        $query = $search->getQuery();

        return $this->addPaginator($data, $query);
    }
}
