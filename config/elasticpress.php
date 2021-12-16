<?php

declare(strict_types=1);

return [
    'indexables' => [
        'openwob-item'
    ],
    'postStatus' => [
        'publish'
    ],
    'language'   => 'dutch',
    'expire'     => [
        'offset' => '14d',
        'decay'  => '0.5',
    ],
    'search' => [
        'weight' => 2
    ],
    'mapping' => [
        'file' => OW_ROOT_PATH . '/src/OpenWOB/ElasticPress/mappings/7-0.php'
    ]
];
