<?php

return [
    'escape_element' => [
        'id'         => 'escape_element',
        'title'      => __('Escape element', 'openpub-base'),
        'post_types' => ['openpub-item'],
        'context'    => 'normal',
        'priority'   => 'low',
        'autosave'   => true,
        'fields'     => [
            'settings' => [
                [
                    'name'    => __('Enable escape element', 'openpub-base'),
                    'desc'    => __('Show escape element on portal page.', 'openpub-base'),
                    'id'      => 'escape_element_active',
                    'type'    => 'radio',
                    'options' => [
                        '1' => __('Yes', 'openpub-base'),
                        '0' => __('No', 'openpub-base'),
                    ],
                    'std'     => '0',
                ],
            ]
        ]
    ]
];
