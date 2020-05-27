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
    private function getExpiredStatus(WP_Post $post): array
    {
        $status = \get_post_meta($post->ID, '_owc_openpub_expirationdate', true);
        if (empty($status)) {
            return [
                    'message' => '',
                    'status'  => false,
                    'on'      => false
            ];
        }
        $status = explode(' ', $status);
        // If no time is defined, add this for compatibility.
        if (1 >= count($status)) {
            $status[] = ' 00:00';
        }
        $timezone = \get_option('timezone_string');
        $date     = \DateTime::createFromFormat('Y-m-d H:i', implode('', $status), new \DateTimeZone($timezone));
        $dateNow  = new \DateTime(null, new \DateTimeZone($timezone));
        return [
            'message' => ($date < $dateNow) ? 'Item is expired' : '',
            'status'  => ($date < $dateNow),
            'on'      => $date
        ];
    }
}
