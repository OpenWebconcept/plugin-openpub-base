<?php

namespace OWC\OpenPub\Base\Foundation;

class Plugin
{

    /**
     * Name of the plugin.
     *
     * @var string
     */
    const NAME = 'openpub-base';

    /**
     * Version of the plugin.
     * Used for setting versions of enqueue scripts and styles.
     *
     * @var string
     */
    const VERSION = '1.0.3';

    /**
     * Path to the root of the plugin.
     *
     * @var string
     */
    protected $rootPath;

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

    public function __construct(string $rootPath)
    {
        $this->rootPath = $rootPath;
        load_plugin_textdomain($this->getName(), false, $this->getName() . '/languages/');

        $this->loader = new Loader;

        $this->config = new Config($this->rootPath . '/config');
        $this->config->setProtectedNodes(['core']);
        $this->config->boot();

        $this->addStartUpHooks();
        $this->addTearDownHooks();
    }

    /**
     * Boot the plugin.
     *
     * @hook plugins_loaded
     *
     * @return bool
     * @throws \Exception
     */
    public function boot(): bool
    {
        $dependencyChecker = new DependencyChecker($this->config->get('core.dependencies'));

        if ($dependencyChecker->failed()) {
            $dependencyChecker->notify();
            deactivate_plugins(plugin_basename($this->rootPath . '/' . $this->getName() . '.php'));

            return false;
        }

        // Set up service providers
        $this->callServiceProviders('register');

        if (is_admin()) {
            $this->callServiceProviders('register', 'admin');
            $this->callServiceProviders('boot', 'admin');
        }

        if ('cli' === php_sapi_name()) {
            $this->callServiceProviders('register', 'cli');
            $this->callServiceProviders('boot', 'cli');
        }

        $this->callServiceProviders('boot');

        // Register the Hook loader.
        $this->loader->addAction('init', $this, 'filterPlugin', 4);
        $this->loader->register();

        return true;
    }

    public function filterPlugin()
    {
        do_action('owc/' . self::NAME . '/plugin', $this);
    }

    /**
     * Call method on service providers.
     *
     * @param string $method
     * @param string $key
     *
     * @throws \Exception
     */
    public function callServiceProviders($method, $key = '')
    {
        $offset   = $key ? "core.providers.{$key}" : 'core.providers';
        $services = $this->config->get($offset);

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
    public function getName()
    {
        return static::NAME;
    }

    /**
     * Get the version of the plugin.
     *
     * @return string
     */
    public function getVersion()
    {
        return static::VERSION;
    }

    /**
     * Return root path of plugin.
     *
     * @return string
     */
    public function getRootPath(): string
    {
        return $this->rootPath;
    }

    /**
     * Startup hooks to initialize the plugin.
     */
    protected function addStartUpHooks()
    {
        /**
         * This hook registers a plugin function to be run when the plugin is activated.
         */
        register_activation_hook(__FILE__, [Hooks::class, 'pluginActivation']);

        /**
         * This hook is run immediately after any plugin is activated, and may be used to detect the activation of plugins.
         * If a plugin is silently activated (such as during an update), this hook does not fire.
         */
        add_action('activated_plugin', [Hooks::class, 'pluginActivated'], 10, 2);
    }

    /**
     * Teardown hooks to cleanup or uninstall the plugin.
     */
    protected function addTearDownHooks()
    {
        /**
         * This hook is run immediately after any plugin is deactivated, and may be used to detect the deactivation of other plugins.
         */
        add_action('deactivated_plugin', [Hooks::class, 'pluginDeactivated'], 10, 2);

        /**
         * This hook registers a plugin function to be run when the plugin is deactivated.
         */
        register_deactivation_hook(__FILE__, [Hooks::class, 'pluginDeactivation']);

        /**
         * Registers the uninstall hook that will be called when the user clicks on the uninstall link that calls for the plugin to uninstall itself.
         * The link won’t be active unless the plugin hooks into the action.
         */
        register_uninstall_hook(__FILE__, [Hooks::class, 'uninstallPlugin']);
    }
}
