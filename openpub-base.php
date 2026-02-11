<?php

/**
 * Plugin Name:       Yard | OpenPub Base
 * Plugin URI:        https://www.openwebconcept.nl/
 * Description:       Acts as foundation for other OpenPub related content plugins. This plugin implements actions to allow for other plugins to add and/or change Custom Posttypes, Metaboxes, Taxonomies, en Posts 2 posts relations.
 * Version:           3.8.0
 * Author:            Yard | Digital Agency
 * Author URI:        https://www.yard.nl/
 * License:           GPL-3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       openpub-base
 * Domain Path:       /languages
 */

/**
 * If this file is called directly, abort.
 */
if (! defined('WPINC')) {
    die;
}

/**
 * Not all the members of the OpenWebconcept are using composer in the root of their project.
 * Therefore they are required to run a composer install inside this plugin directory.
 * In this case the composer autoload file needs to be required.
 *
 * If this plugin is not installed with composer a custom autoloader is used.
 */
if (!class_exists(\OWC\OpenPub\Base\Foundation\Plugin::class)) {
    if (file_exists(__DIR__ . '/vendor/autoload.php')) {
        require_once __DIR__ . '/vendor/autoload.php';
    } else {
        require_once __DIR__ . '/autoloader.php';
        $autoloader = new \OWC\OpenPub\Base\Autoloader();
    }
}

/**
 * Begin execution of the plugin
 *
 * This hook is called once any activated plugins have been loaded. Is generally used for immediate filter setup, or
 * plugin overrides. The plugins_loaded action hook fires early, and precedes the setup_theme, after_setup_theme, init
 * and wp_loaded action hooks.
 */
add_action('plugins_loaded', function () {
    add_action('after_setup_theme', function () {
        $plugin = (new OWC\OpenPub\Base\Foundation\Plugin(__DIR__));
        $plugin->boot();
        do_action('owc/openpub-base/plugin', $plugin);
    });

    add_action('rest_pre_serve_request', function ($served, $result, $request, $server) {
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization');
        }
        return $served;
    }, 10, 4);
}, 10);
