<?php

namespace OWC\OpenPub\Base\Repositories;

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
        $dateNow = new \DateTime('now', new \DateTimeZone($timezone));

        return [
            'meta_query' => [
                [
                    'relation' => 'OR',
                    [
                        'key'     => '_owc_openpub_expirationdate',
                        'value'   => $dateNow->getTimestamp(),
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
