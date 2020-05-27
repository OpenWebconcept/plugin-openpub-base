<?php

namespace OWC\OpenPub\Base\RestAPI;

use OWC\OpenPub\Base\Foundation\ServiceProvider;
use OWC\OpenPub\Base\RestAPI\Controllers\ItemController;
use OWC\OpenPub\Base\RestAPI\Controllers\SearchController;
use OWC\OpenPub\Base\RestAPI\Controllers\ThemeController;
use WP_REST_Server;

class RestAPIServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    private $namespace = 'owc/openpub/v1';

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->plugin->loader->addFilter('rest_api_init', $this, 'registerRoutes');
        $this->plugin->loader->addFilter('owc/config-expander/rest-api/whitelist', $this, 'whitelist', 10, 1);

        $this->registerModelFields();
    }

    /**
     * Register routes on the rest API.
     *
     * Main endpoint.
     * @link https://url/wp-json/owc/openpub/v1
     *
     * Endpoint of the openpub-items, active and inactive.
     * @link https://url/wp-json/owc/openpub/v1/items
     *
     * Endpoint of the openpub-items, active only.
     * @link https://url/wp-json/owc/openpub/v1/items/active
     *
     * Endpoint of the openpub-item detail page.
     * @link https://url/wp-json/owc/openpub/v1/items/{id}
     *
     * Endpoint of the theme-items.
     * @link https://url/wp-json/owc/openpub/v1/themes
     *
     * Endpoint of the theme detail page.
     * @link https://url/wp-json/owc/openpub/v1/themes/{id}
     *
     * Endpoint of searching.
     * @link https://url/wp-json/owc/openpub/v1/search
     *
     * @return void
     */
    public function registerRoutes()
    {
        register_rest_route($this->namespace, 'items/active', [
            'methods'  => WP_REST_Server::READABLE,
            'callback' => [new ItemController($this->plugin), 'getActiveItems'],
        ]);

        register_rest_route($this->namespace, 'items', [
            'methods'  => WP_REST_Server::READABLE,
            'callback' => [new ItemController($this->plugin), 'getItems'],
        ]);

        register_rest_route($this->namespace, 'items/(?P<id>\d+)', [
            'methods'  => WP_REST_Server::READABLE,
            'callback' => [new ItemController($this->plugin), 'getItem'],
        ]);

        register_rest_route($this->namespace, 'themes', [
            'methods'  => WP_REST_Server::READABLE,
            'callback' => [new ThemeController($this->plugin), 'getThemes'],
        ]);

        register_rest_route($this->namespace, 'search', [
            'methods'  => WP_REST_Server::READABLE,
            'callback' => [new SearchController($this->plugin), 'search'],
            'args'     => [],
        ]);
    }

    /**
     * Whitelist endpoints within Config Expander.
     *
     * @param array $whitelist
     * @return array
     */
    public function whitelist(array $whitelist): array
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
     *
     * @return void
     */
    private function registerModelFields(): void
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
