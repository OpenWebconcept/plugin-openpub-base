<?php

namespace OWC\OpenPub\Base\Foundation;

class Config
{
    /**
     * Directory where config files are located.
     *
     * @var string
     */
    protected $path;

    /**
     * Array with names of protected nodes in the config-items.
     *
     * @var array
     */
    protected $protectedNodes = [];

    /**
     * Array with all the config values.
     *
     * @var array
     */
    protected $items = [];

    /**
     * Config repository constructor.
     *
     * Boot the configuration files and get all the files from the
     * config directory and add them to the config array.
     *
     * @param string $path Path to the configuration files.
     * @param array  $items
     */
    public function __construct($path, array $items = [])
    {
        $this->items = $items;
        $this->path  = $path;
        $this->scanDirectory($this->getPath());
    }

    /**
     * Retrieve a specific config value from the configuration repository.
     *
     * @param string $setting
     * @param string $default
     *
     * @return array|mixed
     */
    public function get(string $setting, $default = '')
    {
        if (!$setting) {
            return $default;
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
     *
     * @return void
     */
    public function set($key, $value = null)
    {
        $keys = is_array($key) ? $key : [$key => $value];

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
                if (!isset($tempItems[$part]) || !is_array($tempItems[$part])) {
                    $tempItems[$part] = [];
                }
                $tempItems = &$tempItems[$part];
            }

            $tempItems[array_shift($parts)] = $value;
        }
    }

    /**
     * Return all config values.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Get the path where the files will be fetched from.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Sets the path where the config files are fetched from.
     *
     * @param string $path
     *
     * @return self
     */
    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @param array $nodes
     *
     * @return self
     */
    public function setProtectedNodes($nodes = []): self
    {
        $this->protectedNodes = $nodes;
        return $this;
    }

    /**
     * @param string $path
     *
     * @return void
     */
    private function scanDirectory(string $path): void
    {
        $files = glob($path . '/*', GLOB_NOSORT);

        if (false === $files) {
            return;
        }

        foreach ($files as $file) {
            $fileType = filetype($file);
            if ("dir" == $fileType) {
                $this->scanDirectory($file);
            } else {
                $name  = str_replace('.php', '', basename($file));
                $value = include $file;

                // If its in the first directory just add the file.
                if ($path == $this->path) {
                    $this->items[$name] = $value;
                    continue;
                }

                // Get the path from the starting path.
                $path = str_replace($this->path . '/', '', $path);

                // Build an array from the path.
                $items        = [];
                $items[$name] = $value;
                foreach (array_reverse(explode('/', $path)) as $key) {
                    $items = [$key => $items];
                }

                // Merge it recursively into items
                $this->items = array_merge_recursive($this->items, $items);
            }
        }
    }
}
