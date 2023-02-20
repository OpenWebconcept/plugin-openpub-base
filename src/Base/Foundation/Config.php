<?php

namespace OWC\OpenPub\Base\Foundation;

/**
 * Config object to store, save and retrieve configurations.
 */
class Config
{
    /**
     * Directory where config files are located.
     */
    protected string $path;

    /**
     * Array with names of protected nodes in the config-items.
     */
    protected array $protectedNodes = [];

    /**
     * Array with all the config values.
     */
    protected array $items = [];

    /**
     * Config repository constructor.
     *
     * Boot the configuration files and get all the files from the
     * config directory and add them to the config array.
     */
    public function __construct($path, array $items = [])
    {
        $this->items = $items;
        $this->path = $path;
    }

    /**
     * Boot up the configuration repository.
     */
    public function boot(): void
    {
        $this->scanDirectory($this->getPath());
    }

    /**
     * Retrieve a specific config value from the configuration repository.
     *
     * @param $setting
     *
     * @return array|mixed
     */
    public function get($setting)
    {
        if (! $setting) {
            return $this->all();
        }

        $parts = explode('.', $setting);

        $current = $this->items;

        foreach ($parts as $part) {
            $current = $current[$part];
        }

        return $current;
    }

    /**
     * Set a given configuration value.
     *
     * @param  array|string $key
     * @param  mixed        $value
     */
    public function set($key, $value = null): void
    {
        $keys = is_array($key) ? $key : [ $key => $value ];

        $tempItems = &$this->items;

        foreach ($keys as $key => $value) {
            if (in_array($key, $this->protectedNodes)) {
                continue;
            }

            $parts = explode('.', $key);
            while (1 < count($parts)) {
                $part = array_shift($parts);
                // If the key doesn't exist at this depth, we will just create an empty array
                // to hold the next value, allowing us to create the arrays to hold final
                // values at the correct depth. Then we'll keep digging into the array.
                if (! isset($tempItems[$part]) || ! is_array($tempItems[$part])) {
                    $tempItems[$part] = [];
                }
                $tempItems = &$tempItems[$part];
            }

            $tempItems[array_shift($parts)] = $value;
        }
    }

    /**
     * Return all config values.
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Get the path where the files will be fetched from.
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Sets the path where the config files are fetched from.
     */
    public function setPath(string $path)
    {
        $this->path = $path;
    }

    /**
     * Some nodes must not be changed by outside interference.
     */
    public function setProtectedNodes(array $nodes = [])
    {
        $this->protectedNodes = $nodes;
    }

    /**
     * Scan a given directory for certain files.
     */
    private function scanDirectory(string $path)
    {
        $files = glob($path.'/*', GLOB_NOSORT);

        foreach ($files as $file) {
            $fileType = filetype($file);

            if ("dir" == $fileType) {
                $this->scanDirectory($file);
            } else {
                $name = str_replace('.php', '', basename($file));
                $value = include $file;

                // If its in the first directory just add the file.
                if ($path == $this->path) {
                    $this->items[$name] = $value;
                    continue;
                }

                // Get the path from the starting path.
                $path = str_replace($this->path.'/', '', $path);

                // Build an array from the path.
                $items = [];
                $items[$name] = $value;
                foreach (array_reverse(explode('/', $path)) as $key) {
                    $items = [ $key => $items ];
                }

                // Merge it recursively into items
                $this->items = array_merge_recursive($this->items, $items);
            }
        }
    }
}
