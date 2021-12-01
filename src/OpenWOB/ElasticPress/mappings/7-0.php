<?php declare(strict_types=1);
/**
 * Elasticsearch mapping for ES 7.0
 */

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

return [
    'settings' => [
        /**
         * Filter number of Elasticsearch shards to use in indices
         *
         * @hook ep_default_index_number_of_shards
         * @param  {int} $shards Number of shards
         * @return {int} New number
         */
        'index.number_of_shards'           => apply_filters('ep_default_index_number_of_shards', 5),
        /**
         * Filter number of Elasticsearch replicas to use in indices
         *
         * @hook ep_default_index_number_of_replicas
         * @param  {int} $replicas Number of replicas
         * @return {int} New number
         */
        'index.number_of_replicas'         => apply_filters('ep_default_index_number_of_replicas', 1),
        /**
         * Filter Elasticsearch total field limit for posts
         *
         * @hook ep_total_field_limit
         * @param  {int} $number Number of fields
         * @return {int} New number
         */
        'index.mapping.total_fields.limit' => apply_filters('ep_total_field_limit', 5000),
        /**
         * Filter whether Elasticsearch ignores malformed fields or not.
         *
         * @hook ep_ignore_malformed
         * @param  {bool} $ignore True for ignore
         * @return {bool} New value
         */
        'index.mapping.ignore_malformed'   => apply_filters('ep_ignore_malformed', true),
        /**
         * Filter Elasticsearch max result window for posts
         *
         * @hook ep_max_result_window
         * @param  {int} $number Size of result window
         * @return {int} New number
         */
        'index.max_result_window'          => apply_filters('ep_max_result_window', 1000000),
        /**
         * Filter Elasticsearch maximum shingle difference
         *
         * @hook ep_max_shingle_diff
         * @param  {int} $number Max difference
         * @return {int} New number
         */
        'index.max_shingle_diff'           => apply_filters('ep_max_shingle_diff', 8),
        'analysis'                         => [
            'analyzer'   => [
                'default'          => [
                    'tokenizer'   => 'standard',
                    'filter'      => [ 'ewp_word_delimiter', 'lowercase', 'stop', 'ewp_snowball' ],
                    'char_filter' => [ 'html_strip' ],
                    /**
                     * Filter Elasticsearch default language in mapping
                     *
                     * @hook ep_analyzer_language
                     * @param  {string} $lang Default language
                     * @param {string} $lang_context Language context
                     * @return {string} New language
                     */
                    'language'    => apply_filters('ep_analyzer_language', 'english', 'analyzer_default'),
                ],
                'shingle_analyzer' => [
                    'type'      => 'custom',
                    'tokenizer' => 'standard',
                    'filter'    => [ 'lowercase', 'shingle_filter' ],
                ],
                'ewp_lowercase'    => [
                    'type'      => 'custom',
                    'tokenizer' => 'keyword',
                    'filter'    => [ 'lowercase' ],
                ],
            ],
            'filter'     => [
                'shingle_filter'     => [
                    'type'             => 'shingle',
                    'min_shingle_size' => 2,
                    'max_shingle_size' => 5,
                ],
                'ewp_word_delimiter' => [
                    'type'              => 'word_delimiter',
                    'preserve_original' => true,
                ],
                'ewp_snowball'       => [
                    'type'     => 'snowball',
                    /**
                     * Filter Elasticsearch default language in mapping
                     *
                     * @hook ep_analyzer_language
                     * @param  {string} $lang Default language
                     * @param {string} $lang_context Language context
                     * @return {string} New language
                     */
                    'language' => apply_filters('ep_analyzer_language', 'english', 'filter_ewp_snowball'),
                ],
                'edge_ngram'         => [
                    'side'     => 'front',
                    'max_gram' => 10,
                    'min_gram' => 3,
                    'type'     => 'edgeNGram',
                ],
            ],
            'normalizer' => [
                'lowerasciinormalizer' => [
                    'type'   => 'custom',
                    'filter' => [ 'lowercase', 'asciifolding' ],
                ],
            ],
        ],
    ],
    'mappings' => [
        'date_detection'    => false,
        'dynamic_templates' => [
            [
                'template_meta' => [
                    'path_match' => 'post_meta.*',
                    'mapping'    => [
                        'type'   => 'text',
                        'path'   => 'full',
                        'fields' => [
                            '{name}' => [
                                'type' => 'text',
                            ],
                            'raw'    => [
                                'type'         => 'keyword',
                                'ignore_above' => 10922,
                            ],
                        ],
                    ],
                ],
            ],
            [
                'template_terms' => [
                    'path_match' => 'terms.*',
                    'mapping'    => [
                        'type'       => 'object',
                        'path'       => 'full',
                        'properties' => [
                            'name'             => [
                                'type'   => 'text',
                                'fields' => [
                                    'raw'      => [
                                        'type' => 'keyword',
                                    ],
                                    'sortable' => [
                                        'type'       => 'keyword',
                                        'normalizer' => 'lowerasciinormalizer',
                                    ],
                                ],
                            ],
                            'term_id'          => [
                                'type' => 'long',
                            ],
                            'term_taxonomy_id' => [
                                'type' => 'long',
                            ],
                            'parent'           => [
                                'type' => 'long',
                            ],
                            'slug'             => [
                                'type' => 'keyword',
                            ],
                            'term_order'       => [
                                'type' => 'long',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'term_suggest' => [
                    'path_match' => 'term_suggest_*',
                    'mapping'    => [
                        'type'     => 'completion',
                        'analyzer' => 'default',
                    ],
                ],
            ],
        ],
        'properties'        => [
            'UUID' => [
                'type' => 'long',
            ],
            'ID'                    => [
                'type' => 'keyword',
            ],
            'post_date'             => [
                'type'   => 'date',
                'format' => 'yyyy-MM-dd HH:mm:ss',
            ],
            'post_date_gmt'         => [
                'type'   => 'date',
                'format' => 'yyyy-MM-dd HH:mm:ss',
            ],
            'title'            => [
                'type'   => 'text',
                'fields' => [
                    'title' => [
                        'type'     => 'text',
                        'analyzer' => 'standard',
                    ],
                    'raw'        => [
                        'type'         => 'keyword',
                        'ignore_above' => 10922,
                    ],
                    'sortable'   => [
                        'type'         => 'keyword',
                        'ignore_above' => 10922,
                        'normalizer'   => 'lowerasciinormalizer',
                    ],
                ],
            ],
            'excerpt' => [
                'type' => 'text'
            ],
            'content' => [
                'type' => 'object'
            ],
            'post_name'             => [
                'type'   => 'text',
                'fields' => [
                    'post_name' => [
                        'type' => 'text',
                    ],
                    'raw'       => [
                        'type'         => 'keyword',
                        'ignore_above' => 10922,
                    ],
                ],
            ],
            'post_modified'         => [
                'type'   => 'date',
                'format' => 'yyyy-MM-dd HH:mm:ss',
            ],
            'post_modified_gmt'     => [
                'type'   => 'date',
                'format' => 'yyyy-MM-dd HH:mm:ss',
            ],
            'post_type'             => [
                'type'   => 'text',
                'fields' => [
                    'post_type' => [
                        'type' => 'text',
                    ],
                    'raw'       => [
                        'type' => 'keyword',
                    ],
                ],
            ],
            'post_mime_type'        => [
                'type' => 'keyword',
            ],
            'permalink'             => [
                'type' => 'keyword',
            ]
        ],
    ],
];
