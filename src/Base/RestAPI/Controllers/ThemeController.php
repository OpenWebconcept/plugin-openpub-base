<?php

namespace OWC\OpenPub\Base\RestAPI\Controllers;

use OWC\OpenPub\Base\Repositories\Theme;
use WP_Error;
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
        $themes = (new Theme())->query(apply_filters(
            'owc/openpub/rest-api/items/query',
            $this->getPaginatorParams($request)
        ));

        return $this->response($themes);
    }

    /**
     * Get an individual post theme by slug.
     *
     * @return array|WP_Error
     */
    public function getThemeBySlug(WP_REST_Request $request)
    {
        $slug = $request->get_param('slug');

        $theme = $this->singleThemeQueryBuilder($request);
        $theme = $theme->findBySlug($slug);

        if (! $theme) {
            return new WP_Error(
                'no_theme_found',
                sprintf('Theme with slug "%s" not found', sanitize_text_field($slug)),
                ['status' => 404]
            );
        }

        return $theme;
    }

    public function singleThemeQueryBuilder(WP_REST_Request $request): Theme
    {
        $theme = (new Theme())
            ->query(apply_filters('owc/openpub/rest-api/themes/query/single', []));

        $preview = filter_var($request->get_param('draft-preview'), FILTER_VALIDATE_BOOLEAN);

        if (true === $preview) {
            $theme->query(['post_status' => ['publish', 'draft', 'future']]);
        }

        return $theme;
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
