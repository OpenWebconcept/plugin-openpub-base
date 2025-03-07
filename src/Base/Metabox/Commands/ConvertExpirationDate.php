<?php

namespace OWC\OpenPub\Base\Metabox\Commands;

use DateTime;
use Exception;
use WP_Post;

class ConvertExpirationDate extends AbstractConvert
{
    protected string $command = 'convert:expiration-date';

    protected function convert(WP_Post $item): void
    {
        $timestamp = $this->convertDateToTimeStamp($item);

        if (! $timestamp) {
            return;
        }

        \update_post_meta($item->ID, '_owc_openpub_expirationdate', $timestamp);
    }

    protected function convertDateToTimeStamp(WP_Post $item): ?int
    {
        $date = \get_post_meta($item->ID, '_owc_openpub_expirationdate', true);

        if (empty($date)) {
            return null;
        }

        try {
            $object = new DateTime($date);
        } catch (Exception $e) {
            return null;
        }

        return $object->getTimestamp();
    }
}
