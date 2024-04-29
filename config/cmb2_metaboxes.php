<?php

return [
    'base' => [
        'id'         => 'openpub_metadata_base',
        'title'      => __('Data', 'openpub-base'),
        'object_types' => ['openpub-item'],
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
                    'type'        => 'text_datetime_timestamp',
                    'date_format' => 'd-m-Y',
                    'time_format' => 'H:i:s'
                ]
            ],
        ],
    ],
    'base_links' => [
        'id'         => 'openpub_metadata_links',
        'title'      => __('Links', 'openpub-base'),
        'object_types' => ['openpub-item'],
        'context'    => 'normal',
        'priority'   => 'high',
        'autosave'   => true,
        'fields' => [
            'links'     => [
                'links'   => [
                    'id'         => 'openpub_links_group',
                    'type'       => 'group',
                    'options' => [
                       'add_button' => __('Add new link group', 'openpub-base'),
                       'remove_button'     => __('Remove link group', 'openpub-base'),
                    ],
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
        ]
    ],
    'base_downloads' => [
        'id'         => 'openpub_metadata_downloads',
        'title'      => __('Downloads', 'openpub-base'),
        'object_types' => ['openpub-item'],
        'context'    => 'normal',
        'priority'   => 'high',
        'autosave'   => true,
        'fields' => [
            'downloads' => [
                'heading'   => [
                    'type' => 'heading',
                    'name' => __('Downloads', 'openpub-base'),
                ],
                'downloads' => [
                    'id'         => 'openpub_downloads_group',
                    'type'       => 'group',
                    'options' => [
                        'add_button' => __('Add new download group', 'openpub-base'),
                        'remove_button'     => __('Remove download group', 'openpub-base'),
                     ],
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
        ]
    ],
    'base_other' => [
        'id'         => 'openpub_metadata_other',
        'title'      => __('Other', 'openpub-base'),
        'object_types' => ['openpub-item'],
        'context'    => 'normal',
        'priority'   => 'high',
        'autosave'   => true,
        'fields' => [
            'other' => [
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
            ]
        ]
    ],
	'district_zipcodes' => [
		'id' => 'openpub_metadata_district_zipcodes',
		'title' => __('Zipcodes', 'openpub-base'),
		'object_types' => ['term'],
		'taxonomies' => ['openpub-district'],
		'context' => 'normal',
		'priority' => 'high',
		'autosave' => true,
		'fields' => [
			'zipcodes' => [
				'heading' => [
					'type' => 'heading',
					'name' => __('Zipcodes', 'openpub-base'),
				],
				'zipcodes' => [
					'id' => 'openpub_zipcodes_group',
					'type' => 'group',
					'options' => [
						'add_button' => __('Add new zipcode', 'openpub-base'),
						'remove_button' => __('Remove zipcode', 'openpub-base'),
					],
					'fields' => [
						[
							'id' => 'openpub_zipcode',
							'name' => __('Zipcode', 'openpub-base'),
							'desc' => __('A zipcode that is part of this district. (Only numbers, no letters)', 'openpub-base'),
							'type' => 'number',
						],
					],
				],
			],
		]
	]
];
