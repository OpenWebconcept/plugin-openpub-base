<?php

return [
    'openpub_base'          => [
        'id'             => 'general',
        'title'          => _x('Portal', 'OpenPub instellingen subpagina', 'openpub-base'),
        'settings_pages' => '_owc_openpub_base_settings',
        'tab'            => 'base',
        'fields'         => [
            'portal' => [
                'portal_url'        => [
                    'name' => __('Portal URL', 'openpub-base'),
                    'desc' => __('URL including http(s)://', 'openpub-base'),
                    'id'   => 'setting_portal_url',
                    'type' => 'text',
                ],
                'openpub_item_slug' => [
                    'name' => __('Portal OpenPub item slug', 'openpub-base'),
                    'desc' => __('URL for OpenPub items in the portal, eg "onderwerp"', 'openpub-base'),
                    'id'   => 'setting_portal_openpub_item_slug',
                    'type' => 'text',
                ],
                'openpub_use_portal_url' => [
                    'name' => __('Portal url', 'openpub-base'),
                    'desc' => __('Use portal url in api.', 'openpub-base'),
                    'id'   => 'setting_use_portal_url',
                    'type' => 'checkbox',
                ],
                'openpub_use_escape_element' => [
                    'name' => __('Escape element', 'openpub-base'),
                    'desc' => __("Use an element to leave the website without being able to navigate back via the browser 'Back' button.", 'openpub-base'),
                    'id'   => 'setting_use_escape_element',
                    'type' => 'checkbox',
                ],
                'openpub_enable_show_on' => [
                    'name' => __('Show on', 'openpub-base'),
                    'desc' => __('Used for configuring on which websites an OpenPub item should be displayed on.', 'openpub-base'),
                    'id'   => 'setting_openpub_enable_show_on',
                    'type' => 'checkbox'
                ],
                'openpub_expired_heading' => [
                    'type' => 'heading',
                    'name' => __('Expired', 'openpub-base'),
                    'desc' => __('OpenPub items without an expiration date expire automatically when the field below has a value bigger than 0. Existing OpenPub items without an expiration date will be assigned a value of the published date plus the value given in days. New OpenPub items will have a value of the current date plus the value given in days.', 'openpub-base'),
                ],
                'openpub_expired_auto_after_days' => [
                    'name' => __('Days to expire', 'openpub-base'),
                    'desc' => __('After how many days should an item expire?', 'openpub-base'),
                    'id'   => 'setting_openpub_expired_auto_after_days',
                    'type' => 'number'
                ]
            ],
        ],
    ],
    'elasticsearch' => [
        'id'             => 'elasticsearch',
        'title'          => __('Elasticsearch', 'openpub-base'),
        'settings_pages' => '_owc_openpub_base_settings',
        'tab'            => 'elasticsearch',
        'fields'         => [
            'elasticsearch' => [
                'url'    => [
                    'id'   => 'setting_elasticsearch_url',
                    'name' => __('Instance url', 'openpub-base'),
                    'desc' => __('URL inclusief http(s)://', 'openpub-base'),
                    'type' => 'text',
                ],
                'shield' => [
                    'id'   => 'setting_elasticsearch_shield',
                    'name' => __('Instance shield', 'openpub-base'),
                    'desc' => __('URL inclusief http(s)://', 'openpub-base'),
                    'type' => 'text',
                ],
                'prefix' => [
                    'id'   => 'setting_elasticsearch_prefix',
                    'name' => __('Instance prefix', 'openpub-base'),
                    'desc' => __('Use this prefix to group multiple instances', 'openpub-base'),
                    'type' => 'text',
                ],
            ],
        ],
    ],
];
