<?php

declare(strict_types=1);

/**
 * BasePlugin which sets all the serviceproviders.
 */

namespace OWC\OpenPub\Base\Foundation;

use Illuminate\Container\Container;
use OWC\OpenPub\Base\Foundation\Bootstrap\LoadConfiguration;
use OWC\OpenPub\Base\Foundation\FeatureFlags\FeatureFlagProvider;
use OWC\OpenPub\Base\Foundation\FeatureFlags\FeatureFlagService;

/**
 * BasePlugin which sets all the serviceproviders.
 */
class Plugin extends Container
{
    /**
     * Name of the plugin.
     *
     * @var string
     */
    const NAME = 'openpub-base';

    /**
     * Version of the plugin.
     *
     * @var string
     */
    const VERSION = OWC_OP_VERSION;

    /**
     * Path to the root of the plugin.
     *
     * @var string $basePath
     */
    protected $basePath;

    /**
     * Instance of the configuration repository.
     *
     * @var \OWC\OpenPub\Base\Config
     */
    public $config;

    /**
     * Instance of the Hook loader.
     *
     * @var Loader
     */
    public $loader;

    /** @var Container */
    protected $container;

    /** @var Plugin */
    protected static $instance;

    /**
     * Constructor of the BasePlugin
     *
     * @param string $basePath
     */
    private function __construct(string $basePath = null)
    {
        if (!$basePath) {
            $basePath = __DIR__ .'/../../../';
        }
        $this->setBasePath($basePath);
        \load_plugin_textdomain($this->getName(), false, $this->getName() . '/languages/');
        $this->registerBaseBindings();
    }

    /**
     * Set the base path for the application.
     *
     * @param  string  $basePath
     * @return $this
     */
    public function setBasePath($basePath)
    {
        $this->basePath = rtrim($basePath, '\/');

        return $this;
    }

    /**
     * Return the Plugin instance
     *
     * @param string $basePath
     *
     * @return self
     */
    public static function getInstance($basePath = null) : self
    {
        if (null === static::$instance) {
            static::$instance = new static($basePath);
        }

        return static::$instance;
    }

    /**
     * Register the basic bindings into the container.
     *
     * @return void
     */
    protected function registerBaseBindings()
    {
        static::setInstance($this);
        $this->instance('app', $this);
        $this->instance(Container::class, $this);
        $this->singleton('loader', Loader::class);
        $this->singleton('featureflag', function ($app) {
            return new FeatureFlagService(new FeatureFlagProvider($app->make('config')));
        });
        $this->make(LoadConfiguration::class)->bootstrap($this);
        $this->singleton('dependencychecker', function ($app) {
            return new DependencyChecker(
                $app->make('config')->get('dependencies.required'),
                $app->make('config')->get('dependencies.suggested'),
                new DismissableAdminNotice
            );
        });
    }

    /**
     * Boot the plugin.
     *
     * @hook plugins_loaded
     *
     * @return bool
     */
    public function boot(): bool
    {
        /** @var DependencyChecker $dependencyChecker */
        $dependencyChecker = $this->make('dependencychecker');
        if ($dependencyChecker->hasFailures()) {
            $dependencyChecker->notifyFailed();
            \deactivate_plugins(\plugin_basename(OWC_OP_PLUGIN_FILE));

            return false;
        }

        if ($dependencyChecker->hasSuggestions()) {
            $dependencyChecker->notifySuggestions();
        }

        // Set up service providers
        $this->callServiceProviders('register');

        if (\is_admin()) {
            $this->callServiceProviders('register', 'admin');
            $this->callServiceProviders('boot', 'admin');
        }

        if ('cli' === php_sapi_name()) {
            $this->callServiceProviders('register', 'cli');
            $this->callServiceProviders('boot', 'cli');
        }

        $this->callServiceProviders('boot');

        // Register the Hook loader.
        $this->make('loader')->addAction('init', $this, 'filterPlugin', 4);
        $this->make('loader')->register();

        return true;
    }

    /**
     * Get the path to the application configuration files.
     *
     * @param  string  $path Optionally, a path to append to the config path
     * @return string
     */
    public function configPath($path = '')
    {
        return $this->getBasePath().DIRECTORY_SEPARATOR.'config'.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    /**
     * Allows for hooking into the plugin name.
     *
     * @return void
     */
    public function filterPlugin(): void
    {
        do_action('owc/' . self::NAME . '/plugin', $this);
    }

    /**
     * Call method on service providers.
     *
     * @param string $method
     * @param string $key
     *
     * @return void
     *
     * @throws \Exception
     */
    public function callServiceProviders($method, $key = ''): void
    {
        $offset   = $key ? "core.providers.{$key}" : 'core.providers';
        $services = $this->make('config')->get($offset);

        foreach ($services as $service) {
            if (is_array($service)) {
                continue;
            }

            $service = new $service($this);

            if (!$service instanceof ServiceProvider) {
                throw new \Exception('Provider must be an instance of ServiceProvider.');
            }

            if (method_exists($service, $method)) {
                $service->$method();
            }
        }
    }

    /**
     * Get the name of the plugin.
     *
     * @return string
     */
    public function getName(): string
    {
        return static::NAME;
    }

    /**
     * Get the version of the plugin.
     *
     * @return string
     */
    public function getVersion(): string
    {
        return static::VERSION;
    }

    /**
     * Return root path of plugin.
     *
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->basePath;
    }
}
