<?php
/**
 * Provider which handles the hooks in the WordPress ecosystem.
 */

namespace OWC\OpenPub\Base\Foundation;

/**
 * Provider which handles the hooks in the WordPress ecosystem.
 */
class Loader
{

    /**
     * The array of actions registered with WordPress.
     *
     * @since    2.0.0
     * @var      array $actions The actions registered with WordPress to fire when the plugin loads.
     */
    protected $actions = [];

    /**
     * The array of filters registered with WordPress.
     *
     * @since    2.0.0
     * @var      array $filters The filters registered with WordPress to fire when the plugin loads.
     */
    protected $filters = [];

    /**
     * Add a new action to the collection to be registered with WordPress.
     *
     * @since    2.0.0
     *
     * @param    string $hook          The name of the WordPress action that is being registered.
     * @param    object $component     A reference to the instance of the object on which the action is defined.
     * @param    string $callback      The name of the function definition on the $component.
     * @param    int    $priority      Optional. he priority at which the function should be fired. Default is 10.
     * @param    int    $acceptedArgs  Optional. The number of arguments that should be passed to the $callback.
     *                                 Default is 1.
     *
     * @return void
     */
    public function addAction($hook, $component, $callback, $priority = 10, $acceptedArgs = 1)
    {
        $this->actions = $this->add($this->actions, $hook, $component, $callback, $priority, $acceptedArgs);
    }

    /**
     * Add a new filter to the collection to be registered with WordPress.
     *
     * @since    2.0.0
     *
     * @param    string $hook          The name of the WordPress filter that is being registered.
     * @param    object $component     A reference to the instance of the object on which the filter is defined.
     * @param    string $callback      The name of the function definition on the $component.
     * @param    int    $priority      Optional. he priority at which the function should be fired. Default is 10.
     * @param    int    $acceptedArgs  Optional. The number of arguments that should be passed to the $callback.
     *                                 Default is 1
     *
     * @return void
     */
    public function addFilter($hook, $component, $callback, $priority = 10, $acceptedArgs = 1)
    {
        $this->filters = $this->add($this->filters, $hook, $component, $callback, $priority, $acceptedArgs);
    }

    /**
     * A utility function that is used to register the actions and hooks into a single
     * collection.
     *
     * @since    2.0.0
     *
     * @param    array  $hooks        The collection of hooks that is being registered (that is, actions or filters).
     * @param    string $hook         The name of the WordPress filter that is being registered.
     * @param    object $component    A reference to the instance of the object on which the filter is defined.
     * @param    string $callback     The name of the function definition on the $component.
     * @param    int    $priority     The priority at which the function should be fired.
     * @param    int    $acceptedArgs The number of arguments that should be passed to the $callback.
     *
     * @return   array                                  The collection of actions and filters registered with WordPress.
     */
    protected function add($hooks, $hook, $component, $callback, $priority, $acceptedArgs)
    {
        $hooks[] = [
            'hook'          => $hook,
            'component'     => $component,
            'callback'      => $callback,
            'priority'      => $priority,
            'accepted_args' => $acceptedArgs
        ];

        return $hooks;
    }

    /**
     * Register the filters and actions with WordPress.
     *
     * @since    2.0.0
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->filters as $hook) {
            add_filter(
                $hook['hook'],
                [ $hook['component'], $hook['callback'] ],
                $hook['priority'],
                $hook['accepted_args']
            );
        }

        foreach ($this->actions as $hook) {
            add_action(
                $hook['hook'],
                [ $hook['component'], $hook['callback'] ],
                $hook['priority'],
                $hook['accepted_args']
            );
        }
    }
}
