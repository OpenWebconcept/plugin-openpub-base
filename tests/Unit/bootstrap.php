<?php

/**
 * PHPUnit bootstrap file
 *
 * @package OWC_OpenPub_Base
 */

//$_tests_dir = getenv( 'WP_TESTS_DIR' );
//if ( ! $_tests_dir ) {
//	$_tests_dir = '/tmp/wordpress-tests-lib';
//}
//
//// Give access to tests_add_filter() function.
//require_once $_tests_dir . '/includes/functions.php';
//
//// Start up the WP testing environment.
//require $_tests_dir . '/includes/bootstrap.php';

/**
 * Load dependencies with Composer autoloader.
 */
require __DIR__ . '/../../vendor/autoload.php';

define('WP_PLUGIN_DIR', __DIR__);

/**
 * Bootstrap WordPress Mock.
 */
\WP_Mock::setUsePatchwork( true );
\WP_Mock::bootstrap();

$GLOBALS['openpub-base'] = array(
	'active_plugins' => array( 'openpub-base/openpub-base.php' ),
);

class WP_CLI {
	public static function add_command() {}
}
