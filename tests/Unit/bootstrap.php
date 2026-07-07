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

define('MINUTE_IN_SECONDS', 60);
define('HOUR_IN_SECONDS', 60 * MINUTE_IN_SECONDS);
define('DAY_IN_SECONDS', 24 * HOUR_IN_SECONDS);
define('WEEK_IN_SECONDS', 7 * DAY_IN_SECONDS);
define('MONTH_IN_SECONDS', 30 * DAY_IN_SECONDS);
define('YEAR_IN_SECONDS', 365 * DAY_IN_SECONDS);

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
