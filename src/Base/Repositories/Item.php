<?php

namespace OWC\OpenPub\Base\Repositories;

class Item extends AbstractRepository
{
    protected $posttype = 'openpub-item';

    protected static $globalFields = [];

    /**
     * Add parameters to meta_query to remove items with expired date.
     *
     * @return array
     */
    public static function addExpirationParameters(): array
    {
        $timezone = wp_timezone_string();
        $dateNow = new \DateTime('now', new \DateTimeZone($timezone));
        $dateNow = $dateNow->format("Y-m-d H:i");

        return [
            'meta_query' => [
                [
                    'relation' => 'OR',
                    [
                        'key'     => '_owc_openpub_expirationdate',
                        'value'   => $dateNow,
                        'compare' => '>=',
                        'type'    => 'DATETIME',
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
     * Add parameters to meta_query to remove items that are expired or not expired.
     *
     * @param boolean $highlighted
     *
     * @return array
     */
    public static function addHighlightedParameters(bool $highlighted): array
    {
        return [
            'meta_query' => [
                [
                    [
                        'key'     => '_owc_openpub_highlighted_item',
                        'value'   => $highlighted ? 1 : 0,
                        'compare' => '=',
                    ],
                ]
            ],
        ];
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
        return [
            'tax_query' => [
                [
                    'taxonomy' => 'openpub-type',
                    'terms'    => sanitize_text_field($type),
                    'field'    => 'slug',
                    'operator' => 'IN'
                ]
            ]
        ];
    }
}
