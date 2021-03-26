<?php

namespace Yard\OpenWOB\Support;

use WP_Post;
use Yard\OpenWOB\Foundation\Plugin;

abstract class CreatesFields
{

    /**
     * Instance of the Plugin.
     *
     * @var Plugin
     */
    protected $plugin;

    /**
     * Makes sure that the plugin is .
     *
     * @param Plugin $plugin
     */
    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Create an additional field on an array.
     *
     * @param WP_Post $post
     *
     * @return mixed
     */
    abstract public function create(WP_Post $post);
}
