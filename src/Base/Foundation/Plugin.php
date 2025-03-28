<?php

namespace OWC\OpenPub\Base\Foundation;

use OWC\OpenPub\Base\Settings\SettingsPageOptions;
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

class Plugin
{
    public const NAME = 'openpub-base';
    public const VERSION = '3.7.3';

    protected string $rootPath;
    public Config $config;
    public Loader $loader;
    public SettingsPageOptions $settings;

    public function __construct(string $rootPath)
    {
        $this->rootPath = $rootPath;
        $this->loader = new Loader();
        $this->config = new Config($this->rootPath . '/config');
        $this->config->setProtectedNodes(['core']);
        $this->settings = SettingsPageOptions::make();
    }

    public function boot(): bool
    {
        $this->loadTextDomain();
        $this->config->boot();

        $dependencyChecker = new DependencyChecker($this->config->get('core.dependencies'));

        if ($dependencyChecker->failed()) {
            $dependencyChecker->notify();
            deactivate_plugins(\plugin_basename($this->rootPath . '/' . $this->getName() . '.php'));

            return false;
        }

        $this->checkForUpdate();
        $this->registerProviders();

        $this->loader->addAction('init', $this, 'filterPlugin', 4);
        $this->loader->register();

        return true;
    }

    public function loadTextDomain(): void
    {
        load_plugin_textdomain($this->getName(), false, $this->getName() . '/languages/');
    }

    protected function checkForUpdate(): void
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

    protected function registerProviders(): void
    {
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
        do_action('owc/' . self::NAME . '/plugin', $this);
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

            if (! $service instanceof ServiceProvider) {
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
