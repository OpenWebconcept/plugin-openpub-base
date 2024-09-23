<?php
/**
 * Checks if dependencies are valid.
 */

namespace OWC\OpenPub\Base\Foundation;

/**
 * Checks if dependencies are valid.
 */
class DependencyChecker
{
    /**
     * Plugins that need to be checked for.
     */
    private array $dependencies;

    /**
     * Build up array of failed plugins, either because
     * they have the wrong version or are inactive.
     */
    private array $failed = [];

    /**
     * Determine which plugins need to be present.
     */
    public function __construct(array $dependencies)
    {
        $this->dependencies = $dependencies;
    }

    /**
     * Determines if the dependencies are not met.
     */
    public function failed(): bool
    {
        foreach ($this->dependencies as $dependency) {
            switch ($dependency['type']) {
                case 'class':
                    $this->checkClass($dependency);
                    break;
                case 'plugin':
                    $this->checkPlugin($dependency);
                    break;
            }
        }

        return 0 < count($this->failed);
    }

    /**
     * Notifies the administrator which plugins need to be enabled,
     * or which plugins have the wrong version.
     */
    public function notify(): void
    {
        \add_action('admin_notices', function () {
            $list = '<p>' . __(
                'The following plugins are required to use the OpenPub:',
                'openpub-base'
            ) . '</p><ol>';

            foreach ($this->failed as $dependency) {
                $info = isset($dependency['message']) ? ' (' . $dependency['message'] . ')' : '';
                $list .= sprintf('<li>%s%s</li>', $dependency['label'], $info);
            }

            $list .= '</ol>';

            printf('<div class="notice notice-error"><p>%s</p></div>', $list);
        });
    }

    /**
     * Marks a dependency as failed.
     */
    private function markFailed(array $dependency, string $defaultMessage): void
    {
        $this->failed[] = array_merge([
            'message' => $dependency['message'] ?? $defaultMessage,
        ], $dependency);
    }

    /**
     * Checks if required class exists.
     */
    private function checkClass(array $dependency): void
    {
        if (! class_exists($dependency['name'])) {
            $this->markFailed($dependency, __('Class does not exist', 'openpub-base'));

            return;
        }
    }

    /**
     * Check if a plugin is enabled and has the correct version.
     */
    private function checkPlugin(array $dependency): void
    {
        if (! function_exists('is_plugin_active')) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        if (! is_plugin_active($dependency['file'])) {
            $this->markFailed($dependency, __('Inactive', 'openpub-base'));

            return;
        }

        // If there is a version lock set on the dependency...
        if (isset($dependency['version'])) {
            if (! $this->checkVersion($dependency)) {
                $this->markFailed($dependency, __('Minimal version:', 'openpub-base') . ' <b>' . $dependency['version'] . '</b>');
            }
        }
    }

    /**
     * Checks the installed version of the plugin.
     */
    private function checkVersion(array $dependency): bool
    {
        try {
            $file = file_get_contents(WP_PLUGIN_DIR . '/' . $dependency['file']);
        } catch (\Exception $e) {
            return false;
        }

        preg_match('/^(?: ?\* ?Version: ?)(.*)$/m', $file, $matches);
        $version = isset($matches[1]) ? str_replace(' ', '', $matches[1]) : '0.0.0';

        return version_compare($version, $dependency['version'], '>=');
    }
}
