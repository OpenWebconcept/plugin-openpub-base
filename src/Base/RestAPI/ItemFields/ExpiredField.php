<?php

namespace OWC\OpenPub\Base\RestAPI\ItemFields;

use OWC\OpenPub\Base\Support\CreatesFields;
use WP_Post;

class ExpiredField extends CreatesFields
{
    /**
     * Get the expired status to the post.
     */
    public function create(WP_Post $post): array
    {
        return $this->getExpiredStatus($post);
    }

    /**
     * Get expired status of a post, if URL & title are present.
     */
    private function getExpiredStatus(WP_Post $post): array
    {
        $date = \get_post_meta($post->ID, '_owc_openpub_expirationdate', true);
        if (empty($date)) {
            return [
                    'message' => '',
                    'status'  => false,
                    'on'      => false
            ];
        }

        $timezone = \wp_timezone_string();
        $dateNow = new \DateTime('now', new \DateTimeZone($timezone));

        return [
            'message' => ((int) $date < $dateNow->getTimestamp()) ? 'Item is expired' : '',
            'status'  => ((int) $date < $dateNow->getTimestamp()),
            'on'      => (new \DateTime())->setTimestamp((int) $date)->format('Y-m-d H:i')
        ];
    }
}
