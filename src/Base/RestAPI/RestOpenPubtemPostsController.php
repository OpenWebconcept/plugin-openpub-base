<?php

namespace OWC\OpenPub\Base\RestAPI;

use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

class RestOpenPubItemPostsController extends \WP_REST_Posts_Controller
{
    /**
     * Retrieves a single post.
     *
     * @since  4.7.0
     * @access public
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function get_item($request)
    {

        $openpubActive = (bool)get_post_meta($request['id'], '_owc_openpub_active', true);
        if ( ! $openpubActive ) {
            $response = new \WP_Error('rest_post_invalid_id', 'OpenPub-item is not active.', ['status' => 404]);

            return $response;
        }

        $post = $this->get_post($request['id']);
        if ( is_wp_error($post) ) {
            return $post;
        }

        $data     = $this->prepare_item_for_response($post, $request);
        $response = rest_ensure_response($data);

        if ( is_post_type_viewable(get_post_type_object($post->post_type)) ) {
            $response->link_header('alternate', get_permalink($post->ID), ['type' => 'text/html']);
        }

        return $response;
    }
}