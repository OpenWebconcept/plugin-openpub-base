<?php

use OWC\OpenPub\Base\Foundation\Plugin;

$taxonomies = [];
if (Plugin::getInstance()->make('featureflag')->isActive('taxonomies.openpub-audience')) {
    $taxonomies['openpub-audience'] = [
        'object_types' => ['openpub-item'],
        'args'         => [
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'capabilities'      => []
        ],
        'names'        => [
            # Override the base names used for labels:
            'singular' => _x('Audience', 'Taxonomy definition', 'openpub-base'),
            'plural'   => _x('Audiences', 'Taxonomy definition', 'openpub-base'),
            'slug'     => 'openpub-doelgroep'
        ]
    ];
}

if (Plugin::getInstance()->make('featureflag')->isActive('taxonomies.openpub-type')) {
    $taxonomies['openpub-type'] = [
        'object_types' => ['openpub-item'],
        'args'         => [
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'capabilities'      => []
        ],
        'names'        => [
            # Override the base names used for labels:
            'singular' => _x('Type', 'Taxonomy definition', 'openpub-base'),
            'plural'   => _x('Types', 'Taxonomy definition', 'openpub-base'),
            'slug'     => 'openpub-type'
        ]
    ];
}

if (Plugin::getInstance()->make('featureflag')->isActive('taxonomies.openpub-usage')) {
    $taxonomies['openpub-usage']  = [
        'object_types' => ['openpub-item'],
        'args'         => [
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'capabilities'      => []
        ],
        'names'        => [
            # Override the base names used for labels:
            'singular' => _x('Usage', 'Taxonomy definition', 'openpub-base'),
            'plural'   => _x('Usages', 'Taxonomy definition', 'openpub-base'),
            'slug'     => 'openpub-toepassing'
        ]
    ];
}

return $taxonomies;
