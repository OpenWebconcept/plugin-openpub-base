<?php

namespace OWC\OpenPub\Base\RestAPI;

use OWC\OpenPub\Base\Foundation\ServiceProvider;
use OWC\OpenPub\Base\RestAPI\Controllers\ItemController;
use OWC\OpenPub\Base\RestAPI\Controllers\SearchController;
use OWC\OpenPub\Base\RestAPI\Controllers\SettingsController;
use OWC\OpenPub\Base\RestAPI\Controllers\ThemeController;
use OWC\OpenPub\Base\RestAPI\ItemFields\FeaturedImageField;
use WP_REST_Server;

class RestAPIServiceProvider extends ServiceProvider
{
    /** @var string */
    private $namespace = 'owc/openpub/v1';

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->plugin->loader->addFilter('rest_api_init', $this, 'registerRoutes');
        $this->plugin->loader->addFilter('owc/config-expander/rest-api/whitelist', $this, 'whitelist', 10, 1);

        // Before cache purges on the default priority, so a portal refetch never rebuilds from the stale image.
        $featuredImageField = new FeaturedImageField($this->plugin);
        $this->plugin->loader->addAction('updated_post_meta', $featuredImageField, 'clearCacheOnMetaChange', 5, 3);
        $this->plugin->loader->addAction('added_post_meta', $featuredImageField, 'clearCacheOnMetaChange', 5, 3);
        $this->plugin->loader->addAction('edit_attachment', $featuredImageField, 'clearCache', 5, 1);
        $this->plugin->loader->addAction('delete_attachment', $featuredImageField, 'clearCache', 5, 1);

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
     * Endpoint of the openpub-item detail page.
     * @link https://url/wp-json/owc/openpub/v1/items/{slug}
     *
     * Endpoint of the theme-items.
     * @link https://url/wp-json/owc/openpub/v1/themes
     *
     * Endpoint of the theme detail page.
     * @link https://url/wp-json/owc/openpub/v1/themes/{id}
     *
     * Endpoint of the theme detail page.
     * @link https://url/wp-json/owc/openpub/v1/themes/{slug}
     *
     * Endpoint of searching.
     * @link https://url/wp-json/owc/openpub/v1/search
     */
    public function registerRoutes(): void
    {
        $itemController = new ItemController($this->plugin);
        $themeController = new ThemeController($this->plugin);
        $searchController = new SearchController($this->plugin);

        register_rest_route($this->namespace, 'items', [
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => [$itemController, 'getItems'],
            'args'                => $itemController->collectionArguments(),
            'permission_callback' => '__return_true',
        ]);

        register_rest_route($this->namespace, 'items/active', [
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => [$itemController, 'getActiveItems'],
            'args'                => $itemController->collectionArguments(),
            'permission_callback' => '__return_true',
        ]);

        register_rest_route($this->namespace, 'items/(?P<id>\d+)', [
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => [$itemController, 'getItem'],
            'args'                => $itemController->singleArguments(),
            'permission_callback' => '__return_true',
        ]);

        register_rest_route($this->namespace, 'items/(?P<slug>[\w-]+)', [
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => [$itemController, 'getItemBySlug'],
            'args'                => $itemController->singleArguments(),
            'permission_callback' => '__return_true',
        ]);

        register_rest_route($this->namespace, 'themes', [
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => [$themeController, 'getThemes'],
            'args'                => $themeController->paginatorArguments(),
            'permission_callback' => '__return_true',
        ]);

        register_rest_route($this->namespace, 'themes/(?P<slug>[\w-]+)', [
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => [$themeController, 'getThemeBySlug'],
            'args'                => $themeController->singleArguments(),
            'permission_callback' => '__return_true',
        ]);

        register_rest_route($this->namespace, 'search', [
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => [$searchController, 'search'],
            'args'                => $searchController->arguments(),
            'permission_callback' => '__return_true',
        ]);

        register_rest_route($this->namespace, 'settings', [
            'methods'             => WP_REST_Server::READABLE,
            'callback'            => [new SettingsController($this->plugin), 'getSettings'],
            'permission_callback' => '__return_true',
        ]);
    }

    /**
     * Whitelist endpoints within Config Expander.
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
     */
    private function registerModelFields(): void
    {
        // Add global fields for all Models.
        foreach ($this->plugin->config->get('api.models') as $posttype => $data) {
            foreach ($data['fields'] as $key => $creator) {
                $class = '\OWC\OpenPub\Base\Repositories\\' . ucfirst($posttype);
                if (!class_exists($class)) {
                    throw new \Exception(sprintf('%s does not exist', $class));
                }
                $creator = new $creator($this->plugin);
                $class::addGlobalField($key, $creator, function () use ($creator) {
                    return $creator->executeCondition()();
                });
            }
        }
    }
}
