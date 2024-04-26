<?php

return [
    'show_on' => [
        'id' => 'show_on',
        'title' => __('External', 'openpub-base'),
        'object_types' => ['openpub-item'],
        'context' => 'normal',
        'priority' => 'low',
        'autosave' => true,
        'fields' => [
            'settings' => [
                [
                    'name' => __('Show on', 'openpub-base'),
                    'desc' => __('Select websites where this item should be displayed on.', 'openpub-base'),
                    'id' => 'show_on_active',
                    'type' => 'taxonomy_multicheck',
                    'taxonomy' => 'openpub-show-on',
                ],
            ],
        ],
    ],
];
