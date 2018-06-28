<?php

return [
	'posttypes_info' => [
		'openpub-item'        =>
			[
				'id'    => 'openpub-item',
				'title' => _x('OpenPub item', 'P2P titel', 'openpub-base' )
			],
		'openpub-owner'       =>
			[
				'id'    => 'openpub-owner',
				'title' => _x('OpenPub owner', 'P2P titel', 'openpub-base')
			]
	],
	'connections'    => [
		[
			'from'       => 'openpub-item',
			'to'         => 'openpub-item',
			'reciprocal' => false
		],
	]
];