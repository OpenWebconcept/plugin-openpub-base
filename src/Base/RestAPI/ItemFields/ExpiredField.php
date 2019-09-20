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
        return $this->getExpiredStatus($post);
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
                    'message' => '',
                    'status'  => false,
                    'on'         => false
            ];
        }
        $date    = \DateTime::createFromFormat('Y-m-d H:i', $status, new \DateTimeZone(get_option('timezone_string')));
        $dateNow = new \DateTime(null, new \DateTimeZone(get_option('timezone_string')));
        $message = ($date < $dateNow) ? 'Item is expired' : '';
        return [
            'message' => $message,
            'status'  => ($date < $dateNow),
            'on'         => $date
        ];
    }
}
