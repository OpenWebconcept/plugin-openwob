<?php declare(strict_types=1);

return [
    'openwob-type' => [
        'object_types' => ['openwob-item'],
        'args'         => [
            'public' => true,
            'show_in_rest' => true,
            'hierarchical' => false,
            'meta_box_cb' => 'post_categories_meta_box',
            'labels' => [
                'name' => __('Types', OW_LANGUAGE_DOMAIN),
                'singular_name' => __('Type', OW_LANGUAGE_DOMAIN),
                'search_items' => __('Search types', OW_LANGUAGE_DOMAIN),
                'all_items' => __('All types', OW_LANGUAGE_DOMAIN),
                'edit_item' => __('Edit type', OW_LANGUAGE_DOMAIN),
                'view_item' => __('View type', OW_LANGUAGE_DOMAIN),
                'update_item' => __('Update type', OW_LANGUAGE_DOMAIN),
                'add_new_item' => __('Add type', OW_LANGUAGE_DOMAIN),
                'new_item_name' => __('New type name', OW_LANGUAGE_DOMAIN),
                'not_found' => __('No types found', OW_LANGUAGE_DOMAIN)
            ]
        ],
    ],
];
