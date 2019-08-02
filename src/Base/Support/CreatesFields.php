<?php

namespace OWC\OpenPub\Base\Support;

use OWC\OpenPub\Base\Foundation\Plugin;
use WP_Post;

abstract class CreatesFields
{

    /**
     * Instance of the Plugin.
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
