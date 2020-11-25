<?php

return [
    'base' => [
        'id'            => '_owc_openpub_base_settings',
        'option_name'   => '_owc_openpub_base_settings',
        'menu_title'    => __('OpenPub settings', 'openpub-base'),
        'icon_url'      => 'dashicons-admin-settings',
        'submenu_title' => _x('Base', 'OpenPub settings subpage', 'openpub-base'),
        'position'      => 9,
        'columns'       => 1,
        'submit_button' => _x('Submit', 'OpenPub settings subpage', 'openpub-base'),
        'tabs'          => [
            'base'          => _x('General', 'PDC settings tab', 'openpub-base'),
            'elasticsearch' => _x('Elasticsearch', 'OpenPub settings tab', 'openpub-base'),
        ],
    ],
];
