<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Hsdl_Mdt_Search
 *
 * @wordpress-plugin
 * Plugin Name:       HSDL MDT Search
 * Plugin URI:        https://www.eight25media.com/
 * Description:       Provide search bar for MDT search.
 * Version:           1.0.0
 * Author:            E25
 * Author URI:        https://www.eight25media.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       hsdl-mdt-search
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'HSDL_MDT_SEARCH_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-hsdl-mdt-search-activator.php
 */
function activate_hsdl_mdt_search() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-hsdl-mdt-search-activator.php';
	Hsdl_Mdt_Search_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-hsdl-mdt-search-deactivator.php
 */
function deactivate_hsdl_mdt_search() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-hsdl-mdt-search-deactivator.php';
	Hsdl_Mdt_Search_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_hsdl_mdt_search' );
register_deactivation_hook( __FILE__, 'deactivate_hsdl_mdt_search' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-hsdl-mdt-search.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_hsdl_mdt_search() {

	$plugin = new Hsdl_Mdt_Search();
	$plugin->run();

}
run_hsdl_mdt_search();
