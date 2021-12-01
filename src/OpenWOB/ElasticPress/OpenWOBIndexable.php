<?php declare(strict_types=1);

namespace Yard\OpenWOB\ElasticPress;

use ElasticPress\Elasticsearch;
use ElasticPress\Indexable\Post\Post;
use WP_Query;
use Yard\OpenWOB\Foundation\Config;
use Yard\OpenWOB\Repository\OpenWOBRepository;

class OpenWOBIndexable extends Post
{
    /**
     * Indexable slug used for identification
     */
    public $slug = 'openwob-item';

    /** @var Config */
    protected $config;

    /** @var OpenWOBRepository */
    protected $repository;

    /**
     * Create indexable and initialize dependencies
     */
    public function __construct(OpenWOBRepository $repository, Config $config)
    {
        $this->config = $config;
        $this->labels = [
            'plural'   => esc_html__('OpenWOB items', OW_LANGUAGE_DOMAIN),
            'singular' => esc_html__('OpenWOB item', OW_LANGUAGE_DOMAIN),
        ];
        $this->repository = $repository;
        $this->sync_manager = new OpenWOBSyncManager($this->slug);
    }

    /**
     * Send mapping to Elasticsearch
     *
     * @since  3.0
     * @return array
     */
    public function put_mapping()
    {
        $mapping = require $this->config->get('elasticpress.mapping.file');

        return Elasticsearch::factory()->put_mapping($this->get_index_name(), $mapping);
    }

    /**
     * Get the name of the index. Each indexable needs a unique index name
     *
     * @param  int $siteID `null` means current blog.
     * @return string
     */
    public function get_index_name($siteID = null)
    {
        $siteUrl = pathinfo(get_site_url());
        $siteBasename = $siteUrl['basename'];

        if (defined('EP_INDEX_PREFIX') && EP_INDEX_PREFIX) {
            $siteBasename = EP_INDEX_PREFIX . '--' . $siteBasename;
        }

        $buildIndexName = array_filter(
            [
                $siteBasename .'-openwob',
                $siteID,
                $this->getEnvironmentVariable(),
            ]
        );

        return implode('--', $buildIndexName);
    }

    protected function getEnvironmentVariable(): string
    {
        return getenv('APP_ENV');
    }

    /**
     * Prepare a post for syncing
     *
     * @param int $post_id Post ID.
     * @return bool|array
     */
    public function prepare_document($post_id)
    {
        $post = get_post($post_id);

        if (empty($post)) {
            return false;
        }

        $post_date = $post->post_date;
        $post_date_gmt = $post->post_date_gmt;
        $post_modified = $post->post_modified;
        $post_modified_gmt = $post->post_modified_gmt;

        // To prevent infinite loop, we don't queue when updated_postmeta.
        remove_action('updated_postmeta', [ $this->sync_manager, 'action_queue_meta_sync' ], 10);

        $post_args = [
            'ID'                    => $post_id,
            'post_date'             => $post_date,
            'post_date_gmt'         => $post_date_gmt,
            'title'                 => $post->post_title,
            'excerpt'               => $post->post_excerpt,
            'content'               => $this->prepare_meta_types($this->prepare_meta($post)), // post_meta removed in 2.4
            'post_name'             => $post->post_name,
            'post_modified'         => $post_modified,
            'post_modified_gmt'     => $post_modified_gmt,
            'post_type'             => $post->post_type,
            'post_mime_type'        => $post->post_mime_type,
            'permalink'             => \get_permalink($post_id),
            'meta'                  => $this->prepare_meta_types($this->prepare_meta($post)), // post_meta removed in 2.4
        ];

        // Turn back on updated_postmeta hook
        add_action('updated_postmeta', [ $this->sync_manager, 'action_queue_meta_sync' ], 10, 4);

        return $post_args;
    }

    /**
     * Query database for posts
     *
     * @param  array $args Query DB args
     * @return array
     */
    public function query_db($args)
    {
        $items = $this->repository
            ->query(apply_filters('yard/openwob/rest-api/items/query', [
                'post_type'           => ['openwob-item'],
                'posts_per_page'      => $this->get_bulk_items_per_page(),
                'post_status'         => $this->get_indexable_post_status(),
                'ignore_sticky_posts' => true,
                'orderby'             => 'ID',
                'order'               => 'desc',
            ]));

        $args = array_merge($args, $items->getQueryArgs());
        $query = new WP_Query($args);

        return [
            'objects'       => $query->posts ?? [],
            'total_objects' => $query->found_posts,
        ];
    }

    /**
     * Returns indexable post types for the current site
     *
     * @since 0.9
     * @return mixed|void
     */
    public function get_indexable_post_types()
    {
        $post_types = ['openwob-item'];

        /**
         * Filter indexable post types
         *
         * @hook ep_indexable_post_types
         * @param  {array} $post_types Indexable post types
         * @return  {array} New post types
         */
        return apply_filters('ep_indexable_post_types', $post_types);
    }
}
