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
        return [
            'meta_query' => [
                'relation' => 'OR',
                [
                    'key'     => '_owc_openpub_expirationdate',
                    'value'   => date("Y-m-d H:I"),
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
     * Add parameters to meta_query to remove items that are expired or not expired
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
                    'key'  => '_owc_openpub_highlighted_item',
                    'value'   => $highlighted ? 1 : 0,
                    'compare' => '=',
                ],
            ],
        ];
    }
}
