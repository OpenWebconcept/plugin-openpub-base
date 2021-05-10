<?php

namespace OWC\OpenPub\Base\Repositories;

class Item extends AbstractRepository
{
    protected $posttype = 'openpub-item';

    protected static $globalFields = [];

    /**
     * Add additional query arguments.
     *
     * @param array $args
     *
     * @return $this
     */
    public function query(array $args)
    {
        $this->queryArgs = array_merge($this->queryArgs, $args);

        return $this;
    }

    /**
     * Add parameters to meta_query to remove items with expired date.
     *
     * @return array
     */
    public static function addExpirationParameters(): array
    {
        $timezone = \get_option('timezone_string');
        $dateNow  = new \DateTime('now', new \DateTimeZone($timezone));
        $dateNow  = $dateNow->format("Y-m-d H:i");

        return [
            'meta_query' => [
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
                    'key'     => '_owc_openpub_highlighted_item',
                    'value'   => $highlighted ? 1 : 0,
                    'compare' => '=',
                ],
            ],
        ];
    }

    /**
     * Add parameters to tax_query to filter items on selected blog slugs.
     *
     * @param string $blogSlug
     * 
     * @return array
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
}
