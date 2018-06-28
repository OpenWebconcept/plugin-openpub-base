<?php

return [

	'base' => [
		'id'             => 'general',
		'title'          => _x('Portal', 'OpenPub instellingen subpagina', 'openpub-base'),
		'settings_pages' => '_owc_openpub_base_settings',
		'tab' => 'base',
		'fields'         => [
			'portal' => [
				'portal_url'    => [
					'name' => __('Portal URL','openpub-base'),
					'desc' => __('URL including http(s)://','openpub-base'),
					'id'   => 'setting_portal_url',
					'type' => 'text'
				],
				'openpub_item_slug' => [
					'name' => __('Portal OpenPub item slug','openpub-base'),
					'desc' => __('URL for OpenPub items in the portal, eg "onderwerp"','openpub-base'),
					'id'   => 'setting_portal_openpub_item_slug',
					'type' => 'text'
				]
			]
		]
	]
];