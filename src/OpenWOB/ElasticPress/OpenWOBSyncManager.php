<?php

namespace Yard\OpenWOB\ElasticPress;

use ElasticPress\Indexable\Post\SyncManager;
use ElasticPress\Indexables as Indexables;

class OpenWOBSyncManager extends SyncManager
{
    /**
     * Remove blog from index when a site is deleted, archived, or deactivated
     *
     * @param int $blog_id WP Blog ID.
     */
    public function action_delete_blog_from_index($blog_id)
    {
        if ($this->kill_sync()) {
            return;
        }

        $indexable = Indexables::factory()->get($this->indexable_slug);

        /**
         * Filter to whether to keep index on site deletion
         *
         * @hook ep_keep_index
         * @param {bool} $keep True means don't delete index
         * @return {boolean} New value
         */
        if ($indexable->index_exists($blog_id) && ! apply_filters('ep_keep_index', false)) {
            $indexable->delete_index($blog_id);
        }
    }

    /**
     * Delete ES post when WP post is deleted
     *
     * @param int $post_id Post id.
     */
    public function action_delete_post($post_id)
    {
        if ($this->kill_sync()) {
            return;
        }

        /**
         * Filter whether to skip the permissions check on deleting a post
         *
         * @hook ep_post_sync_kill
         * @param  {bool} $bypass True to bypass
         * @param  {int} $post_id ID of post
         * @return {boolean} New value
         */
        if (! current_user_can('edit_post', $post_id) && ! apply_filters('ep_sync_delete_permissions_bypass', false, $post_id)) {
            return;
        }

        $indexable = Indexables::factory()->get($this->indexable_slug);
        $post_type = get_post_type($post_id);

        $indexable_post_types = $indexable->get_indexable_post_types();

        if (! in_array($post_type, $indexable_post_types, true)) {
            // If not an indexable post type, skip delete.
            return;
        }

        /**
         * Fires before post deletion
         *
         * @hook ep_delete_post
         * @param  {int} $post_id ID of post
         */
        do_action('ep_delete_post', $post_id);

        Indexables::factory()->get($this->indexable_slug)->delete($post_id, false);

        /**
         * Make sure to reset sync queue in case an shutdown happens before a redirect
         * when a redirect has already been triggered.
         */
        $this->sync_queue = [];
    }

    /**
     * Sync ES index with what happened to the post being saved
     *
     * @param int $post_id Post id.
     * @since 0.1.0
     */
    public function action_sync_on_update($post_id)
    {
        if ($this->kill_sync()) {
            return;
        }

        $indexable = Indexables::factory()->get($this->indexable_slug);
        $post_type = get_post_type($post_id);

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            // Bypass saving if doing autosave
            // @codeCoverageIgnoreStart
            return;
            // @codeCoverageIgnoreEnd
        }

        /**
         * Filter whether to skip the permissions check on deleting a post
         *
         * @hook ep_post_sync_kill
         * @param  {bool} $bypass True to bypass
         * @param  {int} $post_id ID of post
         * @return {boolean} New value
         */
        if (! apply_filters('ep_sync_insert_permissions_bypass', false, $post_id)) {
            if (! current_user_can('edit_post', $post_id) && (! defined('DOING_CRON') || ! DOING_CRON)) {
                // Bypass saving if user does not have access to edit post and we're not in a cron process.
                return;
            }
        }

        $post = get_post($post_id);

        $indexable_post_statuses = $indexable->get_indexable_post_status();

        // Our post was published, but is no longer, so let's remove it from the Elasticsearch index.
        if (! in_array($post->post_status, $indexable_post_statuses, true)) {
            $this->action_delete_post($post_id);
        } else {
            $indexable_post_types = $indexable->get_indexable_post_types();

            if (in_array($post_type, $indexable_post_types, true)) {
                /**
                 * Fire before post is queued for synxing
                 *
                 * @hook ep_sync_on_transition
                 * @param  {int} $post_id ID of post
                 */
                do_action('ep_sync_on_transition', $post_id);

                /**
                 * Filter to kill post sync
                 *
                 * @hook ep_post_sync_kill
                 * @param {bool} $skip True meanas kill sync for post
                 * @param  {int} $object_id ID of post
                 * @param  {int} $object_id ID of post
                 * @return {boolean} New value
                 */
                if (apply_filters('ep_post_sync_kill', false, $post_id, $post_id)) {
                    return;
                }

                $this->add_to_queue($post_id);
            }
        }
    }

    /**
     * Create mapping and network alias when a new blog is created.
     *
     * @param WP_Site $blog New site object.
     */
    public function action_create_blog_index($blog)
    {
        if (! defined('EP_IS_NETWORK') || ! EP_IS_NETWORK) {
            // @codeCoverageIgnoreStart
            return;
            // @codeCoverageIgnoreEnd
        }

        if ($this->kill_sync()) {
            return;
        }

        $non_global_indexable_objects = Indexables::factory()->get_all(false);

        switch_to_blog($blog->blog_id);

        foreach ($non_global_indexable_objects as $indexable) {
            $indexable->delete_index();
            $indexable->put_mapping();

            $index_name = $indexable->get_index_name($blog->blog_id);
            $indexable->create_network_alias([ $index_name ]);
        }

        restore_current_blog();
    }
}
