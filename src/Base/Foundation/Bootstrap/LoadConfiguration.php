<?php

namespace OWC\OpenPub\Base\Foundation\Bootstrap;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Config\Repository as RepositoryContract;
use OWC\OpenPub\Base\Foundation\Plugin;

class LoadConfiguration
{
    public function bootstrap(Plugin $app)
    {
        $items = [];

        // Next we will spin through all of the configuration files in the configuration
        // directory and load each one into the repository. This will make all of the
        // options available to the developer for use in various parts of this app.
        $app->instance('config', $config = new Repository($items));

        $this->loadConfigurationFiles($app, $config);

        date_default_timezone_set($config->get('app.timezone', 'UTC'));

        mb_internal_encoding('UTF-8');
    }

    /**
     * Load the configuration items from all of the files.
     *
     * @param      $path
     */
    public function loadConfigurationFiles(Plugin $app, RepositoryContract $repository)
    {
        $files = $this->getConfigurationFiles($app);

        foreach ($files as $key => $path) {
            $repository->set($key, require $path);
        }
    }

    /**
     * Get the configuration files for the selected environment
     *
     * @return array
     */
    protected function getConfigurationFiles(Plugin $app): array
    {
        $files = [];

        $configPath = realpath($app->configPath());

        $directory = new \RecursiveDirectoryIterator($configPath);
        $iterator  = new \RecursiveIteratorIterator($directory);
        $iterator  = new \RegexIterator($iterator, "/^.+\.php$/i", \RecursiveRegexIterator::GET_MATCH);
        foreach ($iterator as $file) {
            $directory                         = $this->getNestedDirectory($file[0], $configPath);
            $files[basename($file[0], '.php')] = $configPath .'/'. basename($file[0]);
        }

        ksort($files, SORT_NATURAL);

        return $files;
    }

    /**
     * Get the configuration file nesting path.
     *
     * @param  string $file
     * @param  string $configPath
     * @return string
     */
    protected function getNestedDirectory(string $file, string $configPath): string
    {
        $directory = \dirname($file);

        if ($nested = trim(str_replace($configPath, '', $directory), DIRECTORY_SEPARATOR)) {
            $nested = str_replace(DIRECTORY_SEPARATOR, '.', $nested).'.';
        }

        return $nested;
    }
}
