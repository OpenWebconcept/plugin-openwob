<?php declare(strict_types=1);

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
            'version' => '5.0',
            'file'    => 'meta-box/meta-box.php',
        ],
        [
            'type'     => 'plugin',
            'label'    => 'Meta Box Group',
            'version'  => '1.2.14',
            'file'     => 'metabox-group/meta-box-group.php',
	        'alt_file' => 'meta-box-group/meta-box-group.php',
        ],
		[
			'type'  => 'function',
			'label' => '<a href="https://github.com/johnbillion/extended-cpts" target="_blank">Extended CPT library</a>',
			'name'  => 'register_extended_post_type'
		]
    ],
    'suggested' => [
        [
            'type'    => 'plugin',
            'label'   => 'ElasticPress',
            'version' => '4.0.0',
            'file'    => 'elasticpress/elasticpress.php',
        ]
    ]
];
