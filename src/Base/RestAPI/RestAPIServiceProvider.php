<?php

namespace OWC\OpenPub\Base\RestAPI;

use OWC\OpenPub\Base\Foundation\ServiceProvider;
use OWC\OpenPub\Base\RestAPI\Controllers\ItemController;
use OWC\OpenPub\Base\RestAPI\Controllers\SearchController;
use OWC\OpenPub\Base\RestAPI\Controllers\ThemeController;
use WP_REST_Server;

/**
 *  @OA\Server(
 *    url="https://{site}/wp-json/owc/openpub/v1",
 *    description=""
 *  ).
 *  @OA\Info(
 *    title="Yard | Digital Agency, OpenPUB API",
 *    version="3.0.3",
 *    termsOfService="https://www.yard.nl/",
 *    @OA\Contact(
 *      name="Yard | Digital Agency",
 *      url="https://www.yard.nl/",
 *      email="info@yard.nl"
 *    ),
 *    x={
 *      "logo": {
 *         "url": "https://www.yard.nl/wp-content/themes/theme-fusion/assets/img/logo-yard-da.svg"
 *      },
 *      "description": {
 *         "$ref"="../chapters/description.md"
 *      },
 *      "externalDocs": {
 *         "description": "Find out how to create Github repo for your OpenAPI spec.",
 *         "url": "https://openwebconcept.bitbucket.io/openpub/"
 *       }
 *    },
 *    @OA\License(
 *      name="OpenWebConcept",
 *      url="https://www.openwebconcept.nl/"
 *    )
 * )
 */
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
     * @return void
     */
    public function registerRoutes(): void
    {
        /**
         *  @OA\Get(
         *    path="/items/active",
         *    tags={
         *      "API"
         *    },
         *    operationId="getActiveItems",
         *    summary="Get all active items",
         *    @OA\Response(
         *     response="200",
         *     description="Returns all active items"
         *    )
         * )
         */
        register_rest_route($this->namespace, 'items/active', [
            'methods'  => WP_REST_Server::READABLE,
            'callback' => [new ItemController($this->plugin), 'getActiveItems'],
        ]);

        /**
         *  @OA\Get(
         *    path="/items",
         *    tags={
         *      "API"
         *    },
         *    operationId="getItems",
         *    summary="Get all active and inactive items",
         *    @OA\Response(
         *     response="200",
         *     description="Returns all active and inactive items"
         *    )
         * )
         */
        register_rest_route($this->namespace, 'items', [
            'methods'  => WP_REST_Server::READABLE,
            'callback' => [new ItemController($this->plugin), 'getItems'],
        ]);

        /**
         *  @OA\Get(
         *    path="/items/{id}",
         *    tags={
         *      "API"
         *    },
         *    operationId="getItems",
         *    summary="Get an openpub item by ID",
         *    @OA\Response(
         *     response="200",
         *     description="Get an openpub item by ID"
         *    ),
         *     @OA\Response(
         *     response="404",
         *     description="An OpenPub item with the specified ID was not found."
         *    )
         * )
         */
        register_rest_route($this->namespace, 'items/(?P<id>\d+)', [
            'methods'  => WP_REST_Server::READABLE,
            'callback' => [new ItemController($this->plugin), 'getItem'],
        ]);

        /**
         *  @OA\Get(
         *    path="/items/{slug}",
         *    tags={
         *      "API"
         *    },
         *    operationId="getItemBySlug",
         *    summary="Get an openpub item by slug",
         *    @OA\Response(
         *     response="200",
         *     description="Get an openpub item by slug"
         *    ),
        *     @OA\Response(
         *     response="404",
         *     description="An OpenPub item with the specified slug was not found"
         *    )
         * )
         */
        register_rest_route($this->namespace, 'items/(?P<slug>[\w-]+)', [
            'methods'  => WP_REST_Server::READABLE,
            'callback' => [new Controllers\ItemController($this->plugin), 'getItemBySlug'],
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
                    $creator = new $creator($this->plugin);
                    $class::addGlobalField($key, $creator, function () use ($creator) {
                        return $creator->executeCondition()();
                    });
                }
            }
        }
    }
}
