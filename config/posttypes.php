<?php

use OWC\OpenPub\Base\Models\Item;

return [

    /**
     * Examples of registering post types: https://johnbillion.com/extended-cpts/
     */
    'openpub-item'    => [
        'args'  => [
            # Add the post type to the site's main RSS feed:
            'show_in_feed'  => false,
            # Show all posts on the post type archive:
            'archive'       => [
                'nopaging' => true
            ],
            'public'        => true,
            'show_ui'       => true,
            'supports'      => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions'],
            'menu_icon'     => 'dashicons-format-aside',
            'show_in_rest'  => false,
            'admin_cols'    => [
                'type' => [
                    'title' => _x('Type', 'Admin Filter definition', 'openpub-base'),
                    'taxonomy' => 'openpub-type',
                ],
                'audience' => [
                    'title' => _x('Audience', 'Admin Filter definition', 'openpub-base'),
                    'taxonomy' => 'openpub-audience',
                ],
                'usage' => [
                    'title' => _x('Usage', 'Admin Filter definition', 'openpub-base'),
                    'taxonomy' => 'openpub-usage',
                ],
                'expired' => [
                    'title' => __('Expired', 'openpub-base'),
                    'function' => function () {
                        global $post;

                        $item = (new Item)
                            ->query(apply_filters('owc/openpub/rest-api/items/query/single', []))
                            ->find($post->ID);
                        if (!$item) {
                            echo sprintf('<span style="color: red">%s</span>', __('Expired', 'openpub-base'));
                        } else {
                            $willExpire = get_post_meta($item['id'], '_owc_openpub_expirationdate', true);
                            if ( ! $willExpire ) {
                                echo sprintf('<span>%s</span>', __( 'No expire date', 'openpub-base'));
                            } else {
                                echo sprintf('<span style="color: green">%s %s</span>', __('Will expire on', 'openpub-base'), date_i18n(get_option('date_format') . ', ' . get_option('time_format') , strtotime ($willExpire)));
                            }
                        }
                    },
                ],
                'published' => [
                    'title' => __('Published','openpub-base'),
                    'post_field' => 'post_date',
                    'date_format' => get_option('date_format') .', '. get_option( 'time_format')
                ],
            ],
            # Add a dropdown filter to the admin screen:
            'admin_filters' => [
                'type'     => [
                    'title'    => _x('Type', 'Admin Filter definition', 'openpub-base'),
                    'taxonomy' => 'openpub-type',
                ],
                'audience' => [
                    'title'    => _x('Audience', 'Admin Filter definition', 'openpub-base'),
                    'taxonomy' => 'openpub-audience',
                ],
                'usage'    => [
                    'title'    => _x('Usage', 'Admin Filter definition', 'openpub-base'),
                    'taxonomy' => 'openpub-usage',
                ],
                'aspect'   => [
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
            'singular' => _x('Item', 'Posttype definition', 'openpub-base'),
            'plural'   => _x('Items', 'Posttype definition', 'openpub-base'),
            'name'     => _x('Items', 'post type general name', 'openpub-base')
        ]
    ],
    'openpub-theme'    => [
        'args'  => [

            # Add the post type to the site's main RSS feed:
            'show_in_feed' => false,

            # Show all posts on the post type archive:
            'archive'      => [
                'nopaging' => true
            ],
            'supports'     => ['title', 'editor', 'excerpt', 'revisions', 'thumbnail'],
            'show_in_rest' => true,
            'rest_base'    => 'openpub-thema',
        ],
        'names' => [

            # Override the base names used for labels:
            'singular' => _x('Theme', 'Posttype definition', 'openpub-base'),
            'plural'   => _x('Themes', 'Posttype definition', 'openpub-base'),
            'slug'     => 'openpub-thema'
        ]
    ],
    'openpub-subtheme' => [
        'args'  => [
            # Add the post type to the site's main RSS feed:
            'show_in_feed' => false,

            # Show all posts on the post type archive:
            'archive'      => [
                'nopaging' => true
            ],
            'supports'     => ['title', 'editor', 'excerpt', 'revisions', 'thumbnail', 'page-attributes'],
            'show_in_rest' => true,
            'hierarchical' => true,
            'rest_base'    => 'openpub-subthema',
        ],
        'names' => [

            # Override the base names used for labels:
            'singular' => _x('Subtheme', 'Posttype definition', 'openpub-base'),
            'plural'   => _x('Subthemes', 'Posttype definition', 'openpub-base'),
            'slug'     => 'openpub-subthema'

        ]
    ],
    'openpub-location' => [
        'args'  => [
            # Add the post type to the site's main RSS feed:
            'show_in_feed' => false,

            # Show all posts on the post type archive:
            'archive'      => [
                'nopaging' => true
            ],
            'supports'     => ['title', 'editor', 'excerpt', 'revisions', 'thumbnail', 'page-attributes'],
            'show_in_rest' => true,
            'hierarchical' => true,
            'rest_base'    => 'openpub-location',
        ],
        'names' => [

            # Override the base names used for labels:
            'singular' => _x('Location', 'Posttype definition', 'openpub-base'),
            'plural'   => _x('Locations', 'Posttype definition', 'openpub-base'),
            'slug'     => 'openpub-location'

        ]
    ]
];