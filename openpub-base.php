<?php

/**
 * Plugin Name:       OpenPub Base
 * Plugin URI:        https://www.openwebconcept.nl/
 * Description:       Acts as foundation for other OpenPub related content plugins. This plugin implements actions
 * to allow for other plugins to add and/or change Custom Posttypes, Metaboxes, Taxonomies, en Posts 2 posts relations.
 * Version:           2.0.0
 * Author:            Yard | Digital Agency
 * Author URI:        https://www.yard.nl/
 * License:           GPL-3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       openpub-base
 * Domain Path:       /languages
 */

declare(strict_types=1);

/**
 * If this file is called directly, abort.
 */
if (!defined('WPINC')) {
    die;
}

define('OWC_OP_PLUGIN_FILE', __FILE__);
define('OWC_OP_PLUGIN_SLUG', basename(__FILE__, '.php'));
define('OWC_OP_ROOT_PATH', __DIR__);
define('OWC_OP_VERSION', '1.2.0');

/**
 * manual loaded file: the autoloader.
 */
require_once __DIR__ . '/autoloader.php';
$autoloader = new \OWC\OpenPub\Base\Autoloader();

add_action('plugins_loaded', function () {
    $plugin = (new \OWC\OpenPub\Base\Foundation\Plugin(__DIR__))->boot();
}, 10);
