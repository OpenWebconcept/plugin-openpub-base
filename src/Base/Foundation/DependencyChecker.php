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
     *
     * @var array $dependencies
     */
    private $dependencies;

    /**
     * Build up array of failed plugins, either because
     * they have the wrong version or are inactive.
     *
     * @var array $failed
     */
    private $failed = [];

    /** @var array */
    private $suggestions = [];

    /** @var DismissableAdminNotice */
    protected $dismissableAdminNotice;

    /**
     * Determine which plugins need to be present.
     *
     * @param array $dependencies
     * @param array $suggestions
     */
    public function __construct(array $dependencies, array $suggestions = [], $dismissableAdminNotice = null)
    {
        $this->dependencies           = $dependencies;
        $this->suggestions            = $suggestions;
        $this->dismissableAdminNotice = $dismissableAdminNotice;
    }

    /**
     * Determines if the dependencies are not met.
     *
     * @return bool
     */
    public function hasFailures(): bool
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
     * Determines if the dependencies are not met.
     *
     * @return bool
     */
    public function hasSuggestions(): bool
    {
        foreach ($this->suggestions as $suggestion) {
            switch ($suggestion['type']) {
                case 'class':
                    $this->checkClass($suggestion);
                    break;
                case 'plugin':
                    $this->checkPlugin($suggestion);
                    break;
            }
        }

        return 0 < count($this->suggestions);
    }

    /**
     * Notifies the administrator which plugins need to be enabled,
     * or which plugins have the wrong version.
     *
     * @return void
     */
    public function notifyFailed(): void
    {
        add_action('admin_notices', function () {
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
     * Notifies the administrator which plugins need to be enabled,
     * or which plugins have the wrong version.
     *
     * @return void
     */
    public function notifySuggestions(): void
    {
        add_action('admin_notices', function () {
            if (! $this->dismissableAdminNotice->isAdminNoticeActive('dependency-suggestions-forever')) {
                return;
            }
            $list = '<p>' . __(
                'The following plugins or libraries are suggested and supported to be used with the OpenPub:',
                'openpub-base'
            ) . '</p><ol>';

            foreach ($this->suggestions as $suggestion) {
                $info = (isset($suggestion['message']) and (!empty($suggestion['message']))) ? ' (' . $suggestion['message'] . ')' : '';
                $list .= sprintf('<li>%s%s</li>', $suggestion['label'], $info);
            }

            $list .= '</ol>';

            printf('<div data-dismissible="dependency-suggestions-forever" class="updated notice notice-info is-dismissible"><p>%s</p></div>', $list);
        });
    }

    /**
     * Marks a dependency as failed.
     *
     * @param array  $dependency
     * @param string $defaultMessage
     *
     * @return void
     */
    private function markFailed(array $dependency, string $defaultMessage)
    {
        $this->failed[] = array_merge([
            'message' => $dependency['message'] ?? $defaultMessage,
        ], $dependency);
    }

    /**
     * Checks if required class exists.
     *
     * @param array $dependency
     *
     * @return void
     */
    private function checkClass(array $dependency)
    {
        if (!class_exists($dependency['name'])) {
            $this->markFailed($dependency, __('Class does not exist', 'openpub-base'));

            return;
        }
    }

    /**
     * Check if a plugin is enabled and has the correct version.
     *
     * @param array $dependency
     *
     * @return void
     */
    private function checkPlugin(array $dependency)
    {
        if (!function_exists('is_plugin_active')) {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        if (!is_plugin_active($dependency['file'])) {
            $this->markFailed($dependency, __('Inactive', 'openpub-base'));

            return;
        }

        // If there is a version lock set on the dependency...
        if (isset($dependency['version'])) {
            if (!$this->checkVersion($dependency)) {
                $this->markFailed($dependency, __('Minimal version:', 'openpub-base') . ' <b>' . $dependency['version'] . '</b>');
            }
        }
    }

    /**
     * Checks the installed version of the plugin.
     *
     * @param array $dependency
     *
     * @return bool
     */
    private function checkVersion(array $dependency): bool
    {
        $file = file_get_contents(WP_PLUGIN_DIR . '/' . $dependency['file']);

        preg_match('/^(?: ?\* ?Version: ?)(.*)$/m', $file, $matches);
        $version = isset($matches[1]) ? str_replace(' ', '', $matches[1]) : '0.0.0';

        return version_compare($version, $dependency['version'], '>=');
    }
}
