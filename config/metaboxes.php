<?php

return [
    'base' => [
        'id'         => 'openpub_metadata',
        'title'      => __('Data', 'openpub-base'),
        'post_types' => ['openpub-item'],
        'context'    => 'normal',
        'priority'   => 'high',
        'autosave'   => true,
        'fields'     => [
            'general'   => [
                'highlighted' => [
                    'name' => __('Highlighted item', 'openpub-base'),
                    'desc' => __('Use this option to select current item to be a highlighted (featured) item', 'openpub-base'),
                    'id'   => 'openpub_highlighted_item',
                    'type' => 'checkbox',
                ],
                'synonyms'    => [
                    'name' => __('Synonyms', 'openpub-base'),
                    'desc' => __('Use this option to add an comma separated list of synonyms or related terms', 'openpub-base'),
                    'id'   => 'openpub_tags',
                    'type' => 'textarea',
                ],
                'expiration'  => [
                    'id'          => 'openpub_expirationdate',
                    'name'        => __('Select end date', 'openpub-base'),
                    'type'        => 'datetime',
                    'save_format' => 'Y-m-d H:i',
                ],
            ],
            'links'     => [
                'heading' => [
                    'type' => 'heading',
                    'name' => __('Links', 'openpub-base'),
                ],
                'links'   => [
                    'id'         => 'openpub_links_group',
                    'type'       => 'group',
                    'clone'      => true,
                    'sort_clone' => true,
                    'add_button' => __('Add new link', 'openpub-base'),
                    'fields'     => [
                        [
                            'id'   => 'openpub_links_title',
                            'name' => __('Link title', 'openpub-base'),
                            'desc' => __('Use the title to replace the URL', 'openpub-base'),
                            'type' => 'text',
                        ],
                        [
                            'id'   => 'openpub_links_url',
                            'name' => __('Link URL', 'openpub-base'),
                            'desc' => __('URL including http(s)://', 'openpub-base'),
                            'type' => 'text',
                        ],
                    ],
                ],
            ],
            'downloads' => [
                'heading'   => [
                    'type' => 'heading',
                    'name' => __('Downloads', 'openpub-base'),
                ],
                'downloads' => [
                    'id'         => 'openpub_downloads_group',
                    'type'       => 'group',
                    'clone'      => true,
                    'sort_clone' => true,
                    'add_button' => __('Add new download', 'openpub-base'),
                    'fields'     => [
                        [
                            'id'   => 'openpub_downloads_title',
                            'name' => __('Download title', 'openpub-base'),
                            'desc' => __('Use the title to replace the URL', 'openpub-base'),
                            'type' => 'text',
                        ],
                        [
                            'id'   => 'openpub_downloads_url',
                            'name' => __('Download URL', 'openpub-base'),
                            'desc' => __('URL including http(s)://', 'openpub-base'),
                            'type' => 'text',
                        ],
                    ],
                ],
            ],
            'other'     => [
                'heading' => [
                    'type' => 'heading',
                    'name' => __('Other', 'openpub-base'),
                ],
                'notes'   => [
                    'name' => __('Notes', 'openpub-base'),
                    'desc' => __('(the law, authority, local regulations, etc.)', 'openpub-base'),
                    'id'   => 'openpub_notes',
                    'type' => 'textarea',
                    'cols' => 20,
                    'rows' => 5,
                ],
            ],
        ],
    ],
];
