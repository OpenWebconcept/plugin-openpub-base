<?php

namespace OWC\OpenPub\Base\RestAPI\Controllers;

use OWC\OpenPub\Base\Repositories\Theme;
use WP_Post;
use WP_REST_Request;

class ThemeController extends BaseController
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
    public function getThemes(WP_REST_Request $request)
    {
        $themes = (new Theme())
            ->query(apply_filters('owc/openpub/rest-api/items/query', $this->getPaginatorParams($request)));

        $data  = $themes->all();
        $query = $themes->getQuery();

        return $this->addPaginator($data, $query);
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

        $data = $this->assignFields($data, $post);

        return $data;
    }
}
