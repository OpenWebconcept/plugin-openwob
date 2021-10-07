<?php declare(strict_types=1);

return [

    /**
     * Service Providers.
     */
    'providers'    => [
        /**
         * Global providers.
         */
        Yard\OpenWOB\OpenWOBServiceProvider::class,
        Yard\OpenWOB\ElasticPress\ElasticPressServiceProvider::class,
        /**
         * Providers specific to the admin.
         */
        'admin' => [

        ],
        'cli'   => [
        ],
    ]
];
