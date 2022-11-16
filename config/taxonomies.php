<?php

return [

    /**
     * Examples of registering taxonomies: http://johnbillion.com/extended-cpts/
     */
    'openpub-audience' => [
        'object_types' => ['openpub-item'],
        'args'         => [
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'capabilities'      => []
        ],
        'names'        => [
            'singular' => _x('Audience', 'Taxonomy definition', 'openpub-base'),
            'plural'   => _x('Audiences', 'Taxonomy definition', 'openpub-base'),
            'slug'     => 'openpub-doelgroep'
        ]
    ],

    'openpub-type' => [
        'object_types' => ['openpub-item'],
        'args'         => [
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'capabilities'      => []
        ],
        'names'        => [
            'singular' => _x('Type', 'Taxonomy definition', 'openpub-base'),
            'plural'   => _x('Types', 'Taxonomy definition', 'openpub-base'),
            'slug'     => 'openpub-type'
        ]
    ],
    'openpub-usage'  => [
        'object_types' => ['openpub-item'],
        'args'         => [
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'capabilities'      => []
        ],
        'names'        => [
            'singular' => _x('Usage', 'Taxonomy definition', 'openpub-base'),
            'plural'   => _x('Usages', 'Taxonomy definition', 'openpub-base'),
            'slug'     => 'openpub-toepassing'
        ]
    ],
    'openpub-show-on' => [
        'object_types' => ['openpub-item'],
        'args'         => [
            'show_in_rest'      => false,
            'show_admin_column' => true,
            'capabilities'      => [
                'manage_terms' => 'manage_categories',
                'edit_terms'   => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'manage_categories'
            ]
        ],
        'names'        => [
            'singular' => __('Show on', 'openpub-base'),
            'plural'   => __('Show on', 'openpub-base')
        ]
    ],
];
