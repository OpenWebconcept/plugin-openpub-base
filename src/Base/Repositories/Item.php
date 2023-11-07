<?php

namespace OWC\OpenPub\Base\Repositories;

use DateTime;
use DateTimeZone;

class Item extends AbstractRepository
{
    protected string $posttype = 'openpub-item';
    protected static array $globalFields = [];

    /**
     * Add parameters to meta_query to remove items with expired date.
     */
    public static function addExpirationParameters(): array
    {
        $timezone = wp_timezone_string();
        $dateNowWP = new DateTime('now', new DateTimeZone($timezone));

        return [
            'meta_query' => [
                [
                    'relation' => 'OR',
                    [
                        'key'     => '_owc_openpub_expirationdate',
                        'value'   => $dateNowWP->modify(sprintf('%d hours', self::calculateDifferenceInHours($dateNowWP)))->getTimestamp(),
                        'compare' => '>=',
                    ],
                    [
                        'key'     => '_owc_openpub_expirationdate',
                        'compare' => 'NOT EXISTS',
                    ],
                ]
            ],
        ];
    }

    /**
     * The CMB2 field type 'text_datetime_timestamp' is not working correctly.
     * Inside the datepicker the timezone being used is UTC. The value saved is converted to the timezone of the Wordpress installation.
     * So when '13:55:00' is saved it is actually saved as '15:55:00', assuming the timezone is Europe/Amsterdam (+2).
     * Let's calculate the difference in hours between the two timezones, the outcome is used to modify the DateTime object which is used inside the MetaQuery.
     */
    private static function calculateDifferenceInHours(DateTime $dateNowWP): int
    {
        $dateNowUTC = new DateTime('now', new DateTimeZone('UTC'));

        return (int) ($dateNowWP->getOffset() / 3600) - ($dateNowUTC->getOffset() / 3600);
    }

    public static function addHighlightedParameters(bool $highlighted): array
    {
        $query = [];

        if (! $highlighted) {
            $query['meta_query'] = [
                [
                    [
                        'key'     => '_owc_openpub_highlighted_item',
                        'compare' => 'NOT EXISTS'
                    ],
                ]
            ];
        } else {
            $query['meta_query'] = [
                [
                    [
                        'key'     => '_owc_openpub_highlighted_item',
                        'value'   => 'on',
                        'compare' => '='
                    ],
                ]
            ];
        }

        return $query;
    }

    /**
     * Add parameters to tax_query used for filtering items on selected blog (id) slugs.
     */
    public static function addShowOnParameter(string $blogSlug): array
    {
        return [
            'tax_query' => [
                [
                    'taxonomy' => 'openpub-show-on',
                    'terms'    => sanitize_text_field($blogSlug),
                    'field'    => 'slug',
                    'operator' => 'IN'
                ]
            ]
        ];
    }

    public static function addTypeParameter(string $type): array
    {
        return self::addTypeParameters($type);
    }

    public static function addTypeParameters(string $types): array
    {
        $types = explode(',', $types); // Explode to array for usage inside the query.

        return [
            'tax_query' => [
                [
                    'taxonomy' => 'openpub-type',
                    'terms'    => array_map(function ($type) {
                        return sanitize_text_field($type);
                    }, $types),
                    'field'    => 'slug',
                    'operator' => 'IN'
                ]
            ]
        ];
    }

    public static function addAudienceParameters(string $audiences): array
    {
        $audiences = explode(',', $audiences); // Explode to array for usage inside the query.

        return [
            'tax_query' => [
                [
                    'taxonomy' => 'openpub-audience',
                    'terms'    => array_map(function ($audience) {
                        return sanitize_text_field($audience);
                    }, $audiences),
                    'field'    => 'slug',
                    'operator' => 'IN'
                ]
            ]
        ];
    }
}
