<?php

return [

    /**
     * Examples of registering taxonomies: http://johnbillion.com/extended-cpts/
     */
    'openpub-audience' => [
        'object_types' => ['openpub-item'],
        'args'         => [
            'show_in_rest'      => false,
            'show_admin_column' => true,
            'capabilities'      => [
                //                'manage_terms' => 'manage_openpub_categories',
                //                'edit_terms'   => 'manage_openpub_categories',
                //                'delete_terms' => 'manage_openpub_categories',
                //                'assign_terms' => 'edit_openpub_posts'
            ]
        ],
        'names'        => [
            # Override the base names used for labels:
            'singular' => _x('Audience', 'Taxonomy definition', 'openpub-base'),
            'plural'   => _x('Audiences', 'Taxonomy definition', 'openpub-base'),
            'slug'     => 'openpub-doelgroep'
        ]
    ],

    'openpub-type' => [
        'object_types' => ['openpub-item'],
        'args'         => [
            'show_in_rest'      => false,
            'show_admin_column' => true,
            'capabilities'      => [
                //                'manage_terms' => 'manage_openpub_categories',
                //                'edit_terms'   => 'manage_openpub_categories',
                //                'delete_terms' => 'manage_openpub_categories',
                //                'assign_terms' => 'edit_openpub_posts'
            ]
        ],
        'names'        => [
            # Override the base names used for labels:
            'singular' => _x('Type', 'Taxonomy definition', 'openpub-base'),
            'plural'   => _x('Types', 'Taxonomy definition', 'openpub-base'),
            'slug'     => 'openpub-type'
        ]
    ],

    'openpub-aspect' => [
        'object_types' => ['openpub-item'],
        'args'         => [
            'show_in_rest'      => false,
            'show_admin_column' => true,
            'capabilities'      => [
                //                'manage_terms' => 'manage_openpub_categories',
                //                'edit_terms'   => 'manage_openpub_categories',
                //                'delete_terms' => 'manage_openpub_categories',
                //                'assign_terms' => 'edit_openpub_posts'
            ]
        ],
        'names'        => [
            # Override the base names used for labels:
            'singular' => _x('Aspect', 'Taxonomy definition', 'openpub-base'),
            'plural'   => _x('Aspects', 'Taxonomy definition', 'openpub-base'),
            'slug'     => 'openpub-kenmerk'
        ]
    ],
    'openpub-usage'  => [
        'object_types' => ['openpub-item'],
        'args'         => [
            'show_in_rest'      => false,
            'show_admin_column' => true,
            'capabilities'      => [
                //                'manage_terms' => 'manage_openpub_categories',
                //                'edit_terms'   => 'manage_openpub_categories',
                //                'delete_terms' => 'manage_openpub_categories',
                //                'assign_terms' => 'edit_openpub_posts'
            ]
        ],
        'names'        => [
            # Override the base names used for labels:
            'singular' => _x('Usage', 'Taxonomy definition', 'openpub-base'),
            'plural'   => _x('Usages', 'Taxonomy definition', 'openpub-base'),
            'slug'     => 'openpub-toepassing'
        ]
    ],
    'openpub-owner'  => [
        'object_types' => ['openpub-item'],
        'args'         => [
            'show_in_rest'      => false,
            'show_admin_column' => true,
            'capabilities'      => [
                //                'manage_terms' => 'manage_openpub_categories',
                //                'edit_terms'   => 'manage_openpub_categories',
                //                'delete_terms' => 'manage_openpub_categories',
                //                'assign_terms' => 'edit_openpub_posts'
            ],
        ],
        'names'        => [
            # Override the base names used for labels:
            'singular' => _x('Owner', 'Taxonomy definition', 'openpub-base'),
            'plural'   => _x('Owners', 'Taxonomy definition', 'openpub-base'),
            'slug'     => 'openpub-owner'
        ]
    ]
];