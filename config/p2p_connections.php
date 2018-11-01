<?php

return [

    'posttypes_info' => [
        'openpub-item'     =>
        [
            'id'    => 'openpub-item',
            'title' => __('Connected item(s)', 'openpub-base'),
        ],
        'openpub-theme'    =>
        [
            'id'    => 'openpub-theme',
            'title' => __('Theme', 'openpub-base'),
        ],
        'openpub-subtheme' =>
        [
            'id'    => 'openpub-subtheme',
            'title' => __('Subtheme', 'openpub-base'),
        ],
        'openpub-location' =>
        [
            'id'    => 'openpub-location',
            'title' => __('Locations', 'openpub-base'),
        ],
    ],
    'connections'    => [
        [
            'from'       => 'openpub-item',
            'to'         => 'openpub-theme',
            'reciprocal' => true,
        ],
        [
            'from'       => 'openpub-item',
            'to'         => 'openpub-subtheme',
            'reciprocal' => true,
        ],
        [
            'from'       => 'openpub-theme',
            'to'         => 'openpub-subtheme',
            'reciprocal' => true,
        ],
        [
            'from'       => 'openpub-item',
            'to'         => 'openpub-location',
            'reciprocal' => true,
        ],
        [
            'from'       => 'openpub-item',
            'to'         => 'openpub-item',
            'reciprocal' => true,
        ],
    ],

];
