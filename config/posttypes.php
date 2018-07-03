<?php

return [

    /**
     * Examples of registering post types: https://johnbillion.com/extended-cpts/
     */
    'openpub-item' => [
        'args'  => [
            # Add the post type to the site's main RSS feed:
            'show_in_feed'  => false,
            # Show all posts on the post type archive:
            'archive'       => [
                'nopaging' => true
            ],
            'public'        => false,
            'show_ui'       => true,
            'supports'      => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions'],
            'menu_icon'     => 'dashicons-format-aside',
            'show_in_rest'  => false,
            'admin_cols'    => [],
            # Add a dropdown filter to the admin screen:
            'admin_filters' => [
                'owner'      => [
                    'title'    => _x('Owner', 'Admin Filter definition', 'openpub-base'),
                    'taxonomy' => 'openpub-owner',
                ],
                'type'       => [
                    'title'    => _x('Type', 'Admin Filter definition', 'openpub-base'),
                    'taxonomy' => 'openpub-type',
                ],
                'audience'   => [
                    'title'    => _x('Audience', 'Admin Filter definition', 'openpub-base'),
                    'taxonomy' => 'openpub-audience',
                ],
                'usage'      => [
                    'title'    => _x('Usage', 'Admin Filter definition', 'openpub-base'),
                    'taxonomy' => 'openpub-usage',
                ],
                'aspect'     => [
                    'title'    => _x('Aspect', 'Admin Filter definition', 'openpub-base'),
                    'taxonomy' => 'openpub-aspect',
                ],
            ],
            'labels'        => [
                'singular_name'      => _x('OpenPub item', 'post type singular name', 'openpub-base'),
                'menu_name'          => _x('OpenPub items', 'admin menu', 'openpub-base'),
                'name_admin_bar'     => _x('OpenPub item', 'add new on admin bar', 'openpub-base'),
                'add_new'            => _x('Add new OpenPub', '', 'openpub-base'),
                'add_new_item'       => __('Add new OpenPub', 'openpub-base'),
                'new_item'           => __('New OpenPub', 'openpub-base'),
                'edit_item'          => __('Edit OpenPub', 'openpub-base'),
                'view_item'          => __('View OpenPub', 'openpub-base'),
                'all_items'          => __('All OpenPub items', 'openpub-base'),
                'search_items'       => __('Search OpenPub items', 'openpub-base'),
                'parent_item_colon'  => __('Parent OpenPub items:', 'openpub-base'),
                'not_found'          => __('No OpenPub items found.', 'openpub-base'),
                'not_found_in_trash' => __('No OpenPub items found in trash.', 'openpub-base')
            ]
        ],
        # Override the base names used for labels:
        'names' => [
            'slug'     => 'openpub-item',
            'singular' => _x('OpenPub item', 'Posttype definition', 'openpub-base'),
            'plural'   => _x('OpenPub items', 'Posttype definition', 'openpub-base'),
            'name'     => _x('OpenPub items', 'post type general name', 'openpub-base')
        ]
    ]
];