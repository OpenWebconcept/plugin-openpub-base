<?php

namespace OWC\OpenPub\Base\RestAPI\Controllers;

use OWC\OpenPub\Base\Repositories\Search;
use WP_REST_Request;

class SearchController extends ItemController
{
    /**
     * Search all items.
     *
     * @throws \ReflectionException|\OWC\OpenPub\Base\Exceptions\PropertyNotExistsException
     */
    public function search(WP_REST_Request $request): array
    {
        $search = (new Search($request))
            ->query(['post_type' => 'any'])
            ->query(apply_filters('owc/openpub/rest-api/search/query', $this->getPaginatorParams($request)));

        return $this->response($search);
    }
}
