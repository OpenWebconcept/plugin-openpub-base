<?php

namespace OWC\OpenPub\Base\RestAPI;

use OWC\OpenPub\Base\Foundation\ServiceProvider;
use OWC\OpenPub\Base\RestAPI\Controllers\ItemController;
use OWC\OpenPub\Base\RestAPI\Controllers\SearchController;
use OWC\OpenPub\Base\RestAPI\Controllers\ThemeController;

class RestAPIServiceProvider extends ServiceProvider
{
    private $namespace = 'owc/openpub/v1';

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->plugin->loader->addFilter('rest_api_init', $this, 'registerRoutes');
        $this->plugin->loader->addFilter('owc/config-expander/rest-api/whitelist', $this, 'whitelist', 10, 1);

        $this->registerModelFields();
    }

    /**
     * Register routes on the rest API.
     *
     * @return void
     */
    public function registerRoutes()
    {
        register_rest_route($this->namespace, 'items', [
            'methods'  => 'GET',
            'callback' => [new ItemController($this->plugin), 'getItems'],
        ]);

        register_rest_route($this->namespace, 'items/(?P<id>\d+)', [
            'methods'  => 'GET',
            'callback' => [new ItemController($this->plugin), 'getItem'],
        ]);

        register_rest_route($this->namespace, 'themes', [
            'methods'  => 'GET',
            'callback' => [new ThemeController($this->plugin), 'getThemes'],
        ]);

        register_rest_route($this->namespace, 'search', [
            'methods'  => 'GET',
            'callback' => [new SearchController($this->plugin), 'search'],
            'args'     => [],
        ]);
    }

    /**
     * Whitelist endpoints within Config Expander.
     *
     * @param $whitelist
     *
     * @return array
     */
    public function whitelist($whitelist): array
    {
        // Remove default root endpoint
        unset($whitelist['wp/v2']);

        $whitelist[$this->namespace] = [
            'endpoint_stub' => '/' . $this->namespace,
            'methods'       => ['GET'],
        ];

        return $whitelist;
    }

    /**
     * Register fields for all configured posttypes.
     */
    private function registerModelFields()
    {
        // Add global fields for all Models.
        foreach ($this->plugin->config->get('api.models') as $posttype => $data) {
            foreach ($data['fields'] as $key => $creator) {
                $class = '\OWC\OpenPub\Base\Models\\' . ucfirst($posttype);
                if (class_exists($class)) {
                    $class::addGlobalField($key, new $creator($this->plugin));
                }
            }
        }
    }
}
