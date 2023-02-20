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
     */
    protected array $actions = [];

    /**
     * The array of filters registered with WordPress.
     */
    protected array $filters = [];

    /**
     * Add a new action to the collection to be registered with WordPress.
     */
    public function addAction(string $hook, $component, string $callback, int $priority = 10, int $acceptedArgs = 1): void
    {
        $this->actions = $this->add($this->actions, $hook, $component, $callback, $priority, $acceptedArgs);
    }

    /**
     * Add a new filter to the collection to be registered with WordPress.
     */
    public function addFilter(string $hook, $component, string $callback, int $priority = 10, int $acceptedArgs = 1)
    {
        $this->filters = $this->add($this->filters, $hook, $component, $callback, $priority, $acceptedArgs);
    }

    /**
     * A utility function that is used to register the actions and hooks into a single
     * collection.
     */
    protected function add(array $hooks, string $hook, $component, string $callback, int $priority, int $acceptedArgs)
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
     */
    public function register(): void
    {
        foreach ($this->filters as $hook) {
            \add_filter(
                $hook['hook'],
                [ $hook['component'], $hook['callback'] ],
                $hook['priority'],
                $hook['accepted_args']
            );
        }

        foreach ($this->actions as $hook) {
            \add_action(
                $hook['hook'],
                [ $hook['component'], $hook['callback'] ],
                $hook['priority'],
                $hook['accepted_args']
            );
        }
    }
}
