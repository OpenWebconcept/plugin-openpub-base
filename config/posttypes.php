<?php

use OWC\OpenPub\Base\Repositories\Item;

return [
    /**
     * Examples of registering post types: https://johnbillion.com/extended-cpts/
     */
    'openpub-item' => [
        'args' => [
            // Add the post type to the site's main RSS feed:
            'show_in_feed' => false,
            // Show all posts on the post type archive:
            'archive' => [
                'nopaging' => true,
            ],
            'public'       => true,
            'show_ui'      => true,
            'supports'     => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'comments', 'author'],
            'menu_icon'    => 'dashicons-format-aside',
            'show_in_rest' => true,
            'admin_cols'   => [
                'author' => [
                    'title'      => __('Author', 'pdc-base'),
                ],
                'type' => [
                    'title'    => _x('Type', 'Admin Filter definition', 'openpub-base'),
                    'taxonomy' => 'openpub-type',
                ],
                'audience' => [
                    'title'    => _x('Audience', 'Admin Filter definition', 'openpub-base'),
                    'taxonomy' => 'openpub-audience',
                ],
                'usage' => [
                    'title'    => _x('Usage', 'Admin Filter definition', 'openpub-base'),
                    'taxonomy' => 'openpub-usage',
                ],
                'expired' => [
                    'title'    => __('Expired', 'openpub-base'),
                    'function' => function () {
                        global $post;

                        $item = (new Item)
                            ->query(apply_filters('owc/openpub/rest-api/items/query/single', array_merge([], (new Item)->addExpirationParameters())))
                            ->find($post->ID);
                        if (!$item) {
                            echo sprintf('<span style="color: red">%s</span>', __('Expired', 'openpub-base'));
                        } else {
                            $willExpire = get_post_meta($item['id'], '_owc_openpub_expirationdate', true);
                            if (!$willExpire) {
                                echo sprintf('<span>%s</span>', __('No expire date', 'openpub-base'));
                            } else {
                                echo sprintf('<span style="color: green">%s %s</span>', __('Will expire on', 'openpub-base'), date_i18n(get_option('date_format') . ', ' . get_option('time_format'), strtotime($willExpire)));
                            }
                        }
                    },
                ],
                'published' => [
                    'title'       => __('Published', 'openpub-base'),
                    'post_field'  => 'post_date',
                    'date_format' => get_option('date_format') . ', ' . get_option('time_format'),
                ],
                'orderby' => [],
            ],
            // Add a dropdown filter to the admin screen:
            'admin_filters' => [
                'type' => [
                    'title'    => _x('Type', 'Admin Filter definition', 'openpub-base'),
                    'taxonomy' => 'openpub-type',
                ],
                'audience' => [
                    'title'    => _x('Audience', 'Admin Filter definition', 'openpub-base'),
                    'taxonomy' => 'openpub-audience',
                ],
                'usage' => [
                    'title'    => _x('Usage', 'Admin Filter definition', 'openpub-base'),
                    'taxonomy' => 'openpub-usage',
                ],
                'aspect' => [
                    'title'    => _x('Aspect', 'Admin Filter definition', 'openpub-base'),
                    'taxonomy' => 'openpub-aspect',
                ],
            ],
            'labels' => [
                'singular_name'      => __('OpenPub item', 'openpub-base'),
                'menu_name'          => __('OpenPub items', 'openpub-base'),
                'name_admin_bar'     => __('OpenPub item', 'openpub-base'),
                'add_new'            => __('Add new OpenPub', 'openpub-base'),
                'add_new_item'       => __('Add new OpenPub', 'openpub-base'),
                'new_item'           => __('New OpenPub', 'openpub-base'),
                'edit_item'          => __('Edit OpenPub', 'openpub-base'),
                'view_item'          => __('View OpenPub', 'openpub-base'),
                'all_items'          => __('All OpenPub items', 'openpub-base'),
                'search_items'       => __('Search OpenPub items', 'openpub-base'),
                'parent_item_colon'  => __('Parent OpenPub items:', 'openpub-base'),
                'not_found'          => __('No OpenPub items found.', 'openpub-base'),
                'not_found_in_trash' => __('No OpenPub items found in trash.', 'openpub-base'),
            ],
        ],
        // Override the base names used for labels:
        'names' => [
            'slug'     => 'openpub-item',
            'singular' => _x('Item', 'Posttype definition', 'openpub-base'),
            'plural'   => _x('Items', 'Posttype definition', 'openpub-base'),
            'name'     => _x('Items', 'post type general name', 'openpub-base'),
        ],
    ],
    'openpub-theme' => [
        'args' => [
            // Exclude from ElasticPress sync.
            'public' => true,
            'labels' => [
                'name'               => __('OpenPub theme', 'openpub-base'),
                'singular_name'      => __('OpenPub theme', 'openpub-base'),
                'add_new'            => __('Add new openpub theme', 'openpub-base'),
                'add_new_item'       => __('Add new openpub theme', 'openpub-base'),
                'edit_item'          => __('Edit openpub theme', 'openpub-base'),
                'new_item'           => __('New openpub theme', 'openpub-base'),
                'view_item'          => __('View openpub theme', 'openpub-base'),
                'view_items'         => __('View openpub themes', 'openpub-base'),
                'search_items'       => __('Search openpub themes', 'openpub-base'),
                'not_found'          => __('No openpub themes found.', 'openpub-base'),
                'not_found_in_trash' => __('No openpub themes found in Trash.', 'openpub-base'),
                'all_items'          => __('All openpub themes', 'openpub-base'),
                'archives'           => __('OpenPub themes archives', 'openpub-base'),
                'attributes'         => __('OpenPub theme attributes', 'openpub-base'),
                'insert_into_item'   => __('Insert into openpub theme', 'openpub-base'),
                'featured_image'     => __('Featured image', 'openpub-base'),
                'set_featured_image' => __('Set featured image', 'openpub-base'),
                'menu_name'          => __('OpenPub themes', 'openpub-base'),
                'name_admin_bar'     => __('OpenPub themes', 'openpub-base'),
                'parent_item_colon'  => __('Parent openpub themes:', 'openpub-base'),
            ],
            'show_in_feed' => false,

            'archive' => [
                'nopaging' => true,
            ],
            'supports'     => ['title', 'editor', 'excerpt', 'revisions', 'thumbnail'],
            'show_in_rest' => true,
            'rest_base'    => 'openpub-thema',
        ],
        'names' => [
            'singular' => _x('OpenPub theme', 'Posttype definition', 'openpub-base'),
            'plural'   => _x('OpenPub themes', 'Posttype definition', 'openpub-base'),
            'slug'     => 'openpub-thema',
        ],
    ],
    'openpub-subtheme' => [
        'args' => [
            // Exclude from ElasticPress sync.
            'public' => false,
            'labels' => [
                'name'               => __('OpenPub subtheme', 'openpub-base'),
                'singular_name'      => __('OpenPub subtheme', 'openpub-base'),
                'add_new'            => __('Add new openpub subtheme', 'openpub-base'),
                'add_new_item'       => __('Add new openpub subtheme', 'openpub-base'),
                'edit_item'          => __('Edit openpub subtheme', 'openpub-base'),
                'new_item'           => __('New openpub subtheme', 'openpub-base'),
                'view_item'          => __('View openpub subtheme', 'openpub-base'),
                'view_items'         => __('View openpub subthemes', 'openpub-base'),
                'search_items'       => __('Search openpub subthemes', 'openpub-base'),
                'not_found'          => __('No openpub subthemes found.', 'openpub-base'),
                'not_found_in_trash' => __('No openpub subthemes found in Trash.', 'openpub-base'),
                'all_items'          => __('All openpub subthemes', 'openpub-base'),
                'archives'           => __('OpenPub subthemes archives', 'openpub-base'),
                'attributes'         => __('OpenPub subtheme attributes', 'openpub-base'),
                'insert_into_item'   => __('Insert into openpub subtheme', 'openpub-base'),
                'featured_image'     => __('Featured image', 'openpub-base'),
                'set_featured_image' => __('Set featured image', 'openpub-base'),
                'menu_name'          => __('OpenPub subtheme', 'openpub-base'),
                'name_admin_bar'     => __('OpenPub subtheme', 'openpub-base'),
                'parent_item_colon'  => __('Parent openpub subthemes:', 'openpub-base'),
            ],
            'show_in_feed' => false,

            // Show all posts on the post type archive:
            'archive' => [
                'nopaging' => true,
            ],
            'supports'     => ['title', 'editor', 'excerpt', 'revisions', 'thumbnail', 'page-attributes'],
            'show_in_rest' => true,
            'hierarchical' => true,
            'rest_base'    => 'openpub-subthema',
        ],
        'names' => [
            // Override the base names used for labels:
            'singular' => _x('OpenPub subtheme', 'Posttype definition', 'openpub-base'),
            'plural'   => _x('OpenPub subthemes', 'Posttype definition', 'openpub-base'),
            'slug'     => 'openpub-subthema',
        ],
    ],
    'openpub-location' => [
        'args' => [
            // Exclude from ElasticPress sync.
            'public' => true,
            // Add the post type to the site's main RSS feed:
            'show_in_feed' => false,

            // Show all posts on the post type archive:
            'archive' => [
                'nopaging' => true,
            ],
            'supports'     => ['title', 'editor', 'excerpt', 'revisions', 'thumbnail', 'page-attributes'],
            'show_in_rest' => true,
            'hierarchical' => true,
            'rest_base'    => 'openpub-location',
        ],
        'names' => [
            // Override the base names used for labels:
            'singular' => _x('OpenPub location', 'Posttype definition', 'openpub-base'),
            'plural'   => _x('OpenPub locations', 'Posttype definition', 'openpub-base'),
            'slug'     => 'openpub-location',
        ],
    ],
];
