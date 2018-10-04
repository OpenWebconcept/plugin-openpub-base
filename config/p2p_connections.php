<?php

return [

    'posttypes_info' => [
        'openpub-item'      =>
            [
                'id'    => 'openpub-item',
                'title' => _x('Connected item(s)', 'P2P titel', 'openpub-base')
            ],
        'openpub-theme'     =>
            [
                'id'    => 'openpub-theme',
                'title' => _x('Theme', 'P2P titel', 'openpub-base')
            ],
        'openpub-subtheme'  =>
            [
                'id'    => 'openpub-subtheme',
                'title' => _x('Subtheme', 'P2P titel', 'openpub-base')
            ],
        'openpub-location' =>
            [
                'id'    => 'openpub-location',
                'title' => _x('Locations', 'P2P titel', 'openpub-base')
            ],
    ],
    'connections'    => [
        [
            'from'       => 'openpub-item',
            'to'         => 'openpub-theme',
            'reciprocal' => true
        ],
        [
            'from'       => 'openpub-item',
            'to'         => 'openpub-subtheme',
            'reciprocal' => true
        ],
        [
            'from'       => 'openpub-theme',
            'to'         => 'openpub-subtheme',
            'reciprocal' => true
        ],
        [
            'from'       => 'openpub-item',
            'to'         => 'openpub-location',
            'reciprocal' => true
        ],
        [
            'from'       => 'openpub-item',
            'to'         => 'openpub-item',
            'reciprocal' => true,
        ],
    ]

];