<?php

return [

    'models' => [

        'item' => [
            /**
             * Custom field creators.
             *
             * [
             *      'creator'   => CreatesFields::class,
             *      'condition' => \Closure
             * ]
             */
            'fields' => [
                'taxonomies' => OWC\OpenPub\Base\RestAPI\ItemFields\TaxonomyField::class,
//                'connected'  => OWC\OpenPub\Base\RestAPI\ItemFields\ConnectedField::class,
                'image'      => OWC\OpenPub\Base\RestAPI\ItemFields\FeaturedImageField::class,
                'downloads'  => OWC\OpenPub\Base\RestAPI\ItemFields\DownloadsField::class,
                'links'      => OWC\OpenPub\Base\RestAPI\ItemFields\LinksField::class,
                'synonyms'   => OWC\OpenPub\Base\RestAPI\ItemFields\SynonymsField::class,
                'notes'   => OWC\OpenPub\Base\RestAPI\ItemFields\NotesField::class,
            ]
        ],
    ]
];