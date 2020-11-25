<?php

return [
    'required' => [
        /**
         * Dependencies upon which the plugin relies.
         *
         * Required: type, label
         * Optional: message
         *
         * Type: plugin
         * - Required: file
         * - Optional: version
         *
         * Type: class
         * - Required: name
         */
        [
            'type'    => 'plugin',
            'label'   => 'RWMB Metabox',
            'version' => '4.14.0',
            'file'    => 'meta-box/meta-box.php',
        ],
        [
            'type'    => 'plugin',
            'label'   => 'Meta Box Group',
            'version' => '1.2.14',
            'file'    => 'metabox-group/meta-box-group.php',
        ],
        [
            'type'  => 'class',
            'label' => '<a href="https://github.com/johnbillion/extended-cpts" target="_blank">Extended CPT library</a>',
            'name'  => 'Extended_CPT',
        ],
    ],
    'suggested' => [
        [
            'type'    => 'plugin',
            'label'   => 'ElasticPress',
            'version' => '2.7.0',
            'file'    => 'elasticpress/elasticpress.php',
            'message' => ''
        ],
    ]
];
