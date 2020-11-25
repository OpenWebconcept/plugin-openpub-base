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
        /**
         * Providers specific to the admin.
         */
        'admin' => [
            OWC\OpenPub\Base\Admin\Settings\SettingsServiceProvider::class,
        ],

        'cli'   => [
            OWC\OpenPub\Base\ElasticPress\ElasticPressServiceProvider::class,
        ],
    ],
];
