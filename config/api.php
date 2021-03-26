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
                'connected'   => Yard\OpenWOB\RestAPI\ItemFields\ConnectedField::class,
                'expired'     => Yard\OpenWOB\RestAPI\ItemFields\ExpiredField::class,
                'highlighted' => Yard\OpenWOB\RestAPI\ItemFields\HighlightedItemField::class,
                'taxonomies'  => Yard\OpenWOB\RestAPI\ItemFields\TaxonomyField::class,
                'image'       => Yard\OpenWOB\RestAPI\ItemFields\FeaturedImageField::class,
                'downloads'   => Yard\OpenWOB\RestAPI\ItemFields\DownloadsField::class,
                'links'       => Yard\OpenWOB\RestAPI\ItemFields\LinksField::class,
                'synonyms'    => Yard\OpenWOB\RestAPI\ItemFields\SynonymsField::class,
                'notes'       => Yard\OpenWOB\RestAPI\ItemFields\NotesField::class,
            ],
        ],
        'theme'  => [
            'fields' => [
                'connected' => Yard\OpenWOB\RestAPI\ItemFields\ConnectedThemeItemField::class,
            ],
        ],
        'search' => [
            'fields' => [
                'connected'  => Yard\OpenWOB\RestAPI\ItemFields\ConnectedField::class,
                'expired'    => Yard\OpenWOB\RestAPI\ItemFields\ExpiredField::class,
                'taxonomies' => Yard\OpenWOB\RestAPI\ItemFields\TaxonomyField::class,
                'image'      => Yard\OpenWOB\RestAPI\ItemFields\FeaturedImageField::class,
                'downloads'  => Yard\OpenWOB\RestAPI\ItemFields\DownloadsField::class,
                'links'      => Yard\OpenWOB\RestAPI\ItemFields\LinksField::class,
                'synonyms'   => Yard\OpenWOB\RestAPI\ItemFields\SynonymsField::class,
                'notes'      => Yard\OpenWOB\RestAPI\ItemFields\NotesField::class,
            ],
        ],
    ],
];
