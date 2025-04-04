<?php

return [

    /**
     * Service Providers.
     */
    'providers'    => [
        /**
         * Global providers.
         */
        OWC\OpenPub\Base\PostType\PostTypeServiceProvider::class,
        OWC\OpenPub\Base\Taxonomy\TaxonomyServiceProvider::class,
        OWC\OpenPub\Base\PostsToPosts\PostsToPostsServiceProvider::class,
        OWC\OpenPub\Base\Metabox\MetaboxServiceProvider::class,
        OWC\OpenPub\Base\RestAPI\RestAPIServiceProvider::class,
        OWC\OpenPub\Base\ElasticPress\ElasticPressServiceProvider::class,
        OWC\OpenPub\Base\Admin\AdminServiceProvider::class,
        OWC\OpenPub\Base\Varnish\VarnishServiceProvider::class,

        /**
         * Providers specific to the admin.
         */
        'admin' => [
            OWC\OpenPub\Base\Settings\SettingsServiceProvider::class,
            OWC\OpenPub\Base\Expired\ExpiredServiceProvider::class,
        ],

        'cli'   => [
            OWC\OpenPub\Base\ElasticPress\ElasticPressServiceProvider::class,
        ],
    ],

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
    'dependencies' => [
        [
            'type'    => 'plugin',
            'label'   => 'CMB2',
            'version' => '2.10.1',
            'file'    => 'cmb2/init.php',
        ],
        [
            'type'  => 'function',
            'label' => '<a href="https://github.com/johnbillion/extended-cpts" target="_blank">Extended CPT library</a>',
            'name'  => 'register_extended_post_type'
        ]
    ],

];
