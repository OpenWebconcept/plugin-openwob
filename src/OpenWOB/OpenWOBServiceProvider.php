<?php declare(strict_types=1);

namespace Yard\OpenWOB;

use WP_Query;
use Yard\OpenWOB\Foundation\ServiceProvider;
use Yard\OpenWOB\RestAPI\RestAPIServiceProvider;

class OpenWOBServiceProvider extends ServiceProvider
{
    const POSTTYPE = 'openwob-item';

    /**
     * The array of posttype definitions from the config
     *
     * @var array
     */
    protected $configPostTypes = [];

    public function register()
    {
        $this->plugin->loader->addAction('wp_insert_post_data', $this, 'fillTitle', 10, 1);
        $this->plugin->loader->addAction('wp_after_insert_post', $this, 'updateSavedPost', 10, 3);
        $this->plugin->loader->addAction('init', $this, 'registerPostTypes');
        $this->plugin->loader->addAction('pre_get_posts', $this, 'orderByPublishedDate');
        $this->plugin->loader->addFilter('rwmb_meta_boxes', $this, 'registerMetaboxes', 10, 1);
        (new RestAPIServiceProvider($this->plugin))->register();
    }

    /**
     * Fill the post_title for the overview.
     */
    public function fillTitle(array $post = []): array
    {
        if (self::POSTTYPE !== $post['post_type']) {
            return $post;
        }

        $post['post_title'] = isset($_POST['wob_Titel']) ? \esc_attr($_POST['wob_Titel']) : $post['post_title'];
        return $post;
    }

    /**
     * Fill the post_title for the overview.
     */
    public function updateSavedPost($post, $update, $post_before): void
    {
        if (self::POSTTYPE !== \get_post_type($post)) {
            return;
        }

        $information = \get_post_meta($post, 'wob_Wobverzoek_informatie', true);
        $timestamp = $information['wob_Tijdstip_laatste_wijziging']['timestamp'] ?? null;

        \update_post_meta($post, 'updated_at', $timestamp);
        \update_post_meta($post, 'wob_UUID', sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff)|0x4000,
            mt_rand(0, 0x3fff)|0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        ));
    }

    /**
     * Register metaboxes.
     *
     * @param $rwmbMetaboxes
     *
     * @return array
     */
    public function registerMetaboxes($rwmbMetaboxes)
    {
        $metaboxes = $this->plugin->config->get('metaboxes') ?? [];

        return array_merge($rwmbMetaboxes, \apply_filters('yard/openwob/before-register-metaboxes', $metaboxes));
    }

    /**
     * Add default order.
     *
     * @param WP_Query $query
     * @return void
     */
    public function orderByPublishedDate(WP_Query $query)
    {
        if (!is_admin()) {
            return;
        }

        if (!$query->is_main_query() || self::POSTTYPE != $query->get('post_type')) {
            return;
        }

        if (isset($_GET['orderby'])) {
            return;
        }

        $query->set('orderby', 'post_date');
        $query->set('order', 'DESC');
    }

    /**
     * register custom posttypes.
     */
    public function registerPostTypes()
    {
        \register_post_type(self::POSTTYPE, [
            'label'              => 'OpenWOB',
            'public'             => true,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_rest'       => true,
            'query_var'          => false,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => [ 'author', 'excerpt']
        ]);
    }
}
