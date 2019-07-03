<?php

namespace OWC\OpenPub\Base\RestAPI\ItemFields;

use OWC\OpenPub\Base\Support\CreatesFields;
use WP_Post;

class ExpiredField extends CreatesFields
{

    /**
     * Get the expired status to the post.
     *
     * @param WP_Post $post
     *
     * @return array
     */
    public function create(WP_Post $post): array
    {
        $expiredStatus = $this->getExpiredStatus($post);
        return [
            'message' => $expiredStatus['message'],
            'status'   => $expiredStatus['status']
        ];
    }

    /**
     * Get expired status of a post, if URL & title are present.
     *
     * @param WP_Post $post
     *
     * @return array
     */
    private function getExpiredStatus(WP_Post $post)
    {
        $status = get_post_meta($post->ID, '_owc_openpub_expirationdate', true);
        if (empty($status)) {
            return [
                    'message' => false,
                    'status' => false
            ];
        }
        $date = \DateTime::createFromFormat('Y-m-d H:i', $status, new \DateTimeZone(get_option('timezone_string')));
        if ($date < date('now')) {
            return [
                'message' => false,
                'status' => false
            ];
        }

        return [
            'message' => 'Item is expired',
            'status' => true
        ];
    }
}
