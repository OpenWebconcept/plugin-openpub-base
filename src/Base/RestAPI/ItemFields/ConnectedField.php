<?php

namespace OWC\OpenPub\Base\RestAPI\ItemFields;

use OWC\OpenPub\Base\Support\CreatesFields;
use WP_Post;

class ConnectedField extends CreatesFields
{
    /**
     * Creates an array of connected posts.
     *
     * @param WP_Post $post
     *
     * @return array
     */
    public function create(WP_Post $post): array
    {
        if (! \function_exists('p2p_type')) {
            return [];
        }

        $connections = array_filter($this->plugin->config->get('p2p_connections.connections'), function ($connection) {
            return in_array('openpub-item', $connection, true);
        });

        $result = [];

        foreach ($connections as $connection) {
            $type            = $connection['from'] . '_to_' . $connection['to'];
            $result[ $type ] = $this->getConnectedItems($post->ID, $type);
        }

        return $result;
    }

    /**
     * Get connected items of a post, for a specific connection type.
     *
     * @param int    $postID
     * @param string $type
     *
     * @return array
     */
    protected function getConnectedItems(int $postID, string $type): array
    {
        $connection = \p2p_type($type);

        if (! $connection) {
            return [
                'error' => sprintf(__('Connection type "%s" does not exist', 'openpub-base'), $type)
            ];
        }

        return array_map(function (WP_Post $post) {
            return [
                'id'      => $post->ID,
                'title'   => $post->post_title,
                'slug'    => $post->post_name,
                'excerpt' => $post->post_excerpt,
                'date'    => $post->post_date
            ];
        }, $connection->get_connected($postID)->posts);
    }
}
