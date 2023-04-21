<?php

namespace OWC\OpenPub\Base\Foundation;

use OWC\OpenPub\Base\Settings\SettingsPageOptions;
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

/**
 * BasePlugin which sets all the serviceproviders.
 */
class Plugin
{
    /**
     * Name of the plugin.
     */
    public const NAME = 'openpub-base';

    /**
     * Version of the plugin.
     * Used for setting versions of enqueue scripts and styles.
     */
    public const VERSION = '3.0.1';

    /**
     * Path to the root of the plugin.
     */
    protected string $rootPath;

    /**
     * Instance of the configuration repository.
     */
    public Config $config;

    /**
     * Instance of the Hook loader.
     */
    public Loader $loader;

    /**
     * Instance of the Settings object.
     */
    public SettingsPageOptions $settings;

    /**
     * Constructor of the BasePlugin
     */
    public function __construct(string $rootPath)
    {
        $this->rootPath = $rootPath;
        \load_plugin_textdomain($this->getName(), false, $this->getName() . '/languages/');

        $this->loader = new Loader();

        $this->config = new Config($this->rootPath . '/config');
        $this->config->setProtectedNodes(['core']);
        $this->config->boot();

        $this->settings = SettingsPageOptions::make();
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
        $dependencyChecker = new DependencyChecker($this->config->get('core.dependencies'));

        if ($dependencyChecker->failed()) {
            $dependencyChecker->notify();
            \deactivate_plugins(\plugin_basename($this->rootPath . '/' . $this->getName() . '.php'));

            return false;
        }

        $this->checkForUpdate();

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
        $this->loader->addAction('init', $this, 'filterPlugin', 4);
        $this->loader->register();

        return true;
    }

    protected function checkForUpdate()
    {
        if (! class_exists(PucFactory::class) || $this->isExtendedClass()) {
            return;
        }

        try {
            $updater = PucFactory::buildUpdateChecker(
                'https://github.com/OpenWebconcept/plugin-openpub-base/',
                $this->rootPath . '/openpub-base.php',
                self::NAME
            );

            $updater->getVcsApi()->enableReleaseAssets();
        } catch (\Throwable $e) {
            error_log($e->getMessage());
        }
    }

    /**
     * Check if current class extends parent class.
     *
     * self::const always refers to the parent class.
     * static::const refers to the child class.
     */
    protected function isExtendedClass(): bool
    {
        return self::NAME !== static::NAME;
    }

    /**
     * Allows for hooking into the plugin name.
     */
    public function filterPlugin(): void
    {
        \do_action('owc/' . self::NAME . '/plugin', $this);
    }

    /**
     * Call method on service providers.
     *
     * @throws \Exception
     */
    public function callServiceProviders(string $method, string $key = ''): void
    {
        $offset = $key ? "core.providers.{$key}" : 'core.providers';
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
     */
    public function getName(): string
    {
        return static::NAME;
    }

    /**
     * Get the version of the plugin.
     */
    public function getVersion(): string
    {
        return static::VERSION;
    }

    /**
     * Return root path of plugin.
     */
    public function getRootPath(): string
    {
        return $this->rootPath;
    }
}
