<?php

return [
    'models' => [
        /**
         * Custom field creators.
         *
         * [
         *      'creator'   => CreatesFields::class,
         *      'condition' => \Closure
         * ]
         */
        'item'   => [
            'fields' => [
                'comments'      => OWC\OpenPub\Base\RestAPI\ItemFields\CommentField::class,
                'connected'     => OWC\OpenPub\Base\RestAPI\ItemFields\ConnectedField::class,
                'portal_url'    => OWC\OpenPub\Base\RestAPI\ItemFields\PortalURL::class,
                'date_modified' => OWC\OpenPub\Base\RestAPI\ItemFields\DateModified::class,
                'downloads'     => OWC\OpenPub\Base\RestAPI\ItemFields\DownloadsField::class,
                'expired'       => OWC\OpenPub\Base\RestAPI\ItemFields\ExpiredField::class,
                'highlighted'   => OWC\OpenPub\Base\RestAPI\ItemFields\HighlightedItemField::class,
                'image'         => OWC\OpenPub\Base\RestAPI\ItemFields\FeaturedImageField::class,
                'links'         => OWC\OpenPub\Base\RestAPI\ItemFields\LinksField::class,
                'notes'         => OWC\OpenPub\Base\RestAPI\ItemFields\NotesField::class,
                'synonyms'      => OWC\OpenPub\Base\RestAPI\ItemFields\SynonymsField::class,
                'taxonomies'    => OWC\OpenPub\Base\RestAPI\ItemFields\TaxonomyField::class,
            ],
        ],
        'theme'  => [
            'fields' => [
                'connected' => OWC\OpenPub\Base\RestAPI\ItemFields\ConnectedThemeItemField::class,
            ],
        ],
        'search' => [
            'fields' => [
                'connected'  => OWC\OpenPub\Base\RestAPI\ItemFields\ConnectedField::class,
                'downloads'  => OWC\OpenPub\Base\RestAPI\ItemFields\DownloadsField::class,
                'expired'    => OWC\OpenPub\Base\RestAPI\ItemFields\ExpiredField::class,
                'image'      => OWC\OpenPub\Base\RestAPI\ItemFields\FeaturedImageField::class,
                'links'      => OWC\OpenPub\Base\RestAPI\ItemFields\LinksField::class,
                'notes'      => OWC\OpenPub\Base\RestAPI\ItemFields\NotesField::class,
                'synonyms'   => OWC\OpenPub\Base\RestAPI\ItemFields\SynonymsField::class,
                'taxonomies' => OWC\OpenPub\Base\RestAPI\ItemFields\TaxonomyField::class,
            ],
        ],
    ],
];
