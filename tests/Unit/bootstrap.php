<?php

/**
 * PHPUnit bootstrap file
 */

/**
 * Load dependencies with Composer autoloader.
 */
require __DIR__ . '/../../vendor/autoload.php';

/**
 * Load all stubs.
 */
$files = glob(__DIR__ .'/../Stubs/WordPress/*.php');
array_map(function ($file) {
    require_once $file;
}, $files);

define('WP_PLUGIN_DIR', __DIR__);
define('WP_DEBUG', false);

/**
 * Bootstrap WordPress Mock.
 */
\WP_Mock::setUsePatchwork(true);
\WP_Mock::bootstrap();

$GLOBALS['openpub-base'] = [
    'active_plugins' => ['openpub-base/openpub-base.php'],
];

if (! function_exists('get_echo')) {

    /**
     * Capture the echo of a callable function.
     *
     * @param Callable $callable
     * @param array $args
     *
     * @return string
     */
    function get_echo(callable $callable, $args = []): string
    {
        ob_start();
        call_user_func_array($callable, $args);

        return ob_get_clean();
    }
}
