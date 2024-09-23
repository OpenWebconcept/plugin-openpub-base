<?php

namespace OWC\OpenPub\Base\RestAPI\ItemFields;

use DateTime;
use DateTimeZone;
use OWC\OpenPub\Base\Support\CreatesFields;
use WP_Post;

class ExpiredField extends CreatesFields
{
    public function create(WP_Post $post): array
    {
        return $this->getExpiredStatus($post);
    }

    /**
     * Get the expired status to the post.
     */
    private function getExpiredStatus(WP_Post $post): array
    {
        $date = \get_post_meta($post->ID, '_owc_openpub_expirationdate', true); // Is timestamp.

        if (empty($date) || ! is_numeric($date)) {
            return [
                'message' => '',
                'status' => false,
                'on' => false
            ];
        }

        $timezone = \wp_timezone_string();

        $dateNow = new DateTime('now', new DateTimeZone($timezone));
        $date = (new DateTime())->setTimestamp($date)->setTimezone(new DateTimeZone($timezone)); // The date is saved in the timezone of the Wordpress installation.

        return [
            'message' => ($date->getTimestamp() < $dateNow->getTimestamp()) ? 'Item is expired' : '',
            'status' => ($date->getTimestamp() < $dateNow->getTimestamp()),
            'on' => $date->format('Y-m-d H:i')
        ];
    }
}
