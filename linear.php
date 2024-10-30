<?php
/**
 * The plugin bootstrap file
 *
 * @link        https://wordpress.org/plugins/linear/
 * @since       1.0.0
 * @package     Linear
 *
 * @wordpress-plugin
 * Plugin Name: Linear
 * Plugin URI:  https://wordpress.org/plugins/linear/
 * Description: Linear listing system WordPress plugin. Enables you to easily display all your real estate listings on your website.
 * Version:     2.7.11
 * Author:      Linear Oy
 * Author URI:  https://linear.fi
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: linear
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) || !defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Currently plugin version. (Semantic Versioning)
 */
define( 'LINEAR_VERSION', '2.7.11' );

/**
 * Current plugin path
 */
define( 'LINEAR_PLUGIN_PATH', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
define( 'LINEAR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

add_action( 'plugins_loaded', function() {
	/**
	 * Runs during plugin activation.
	 */
	register_activation_hook( __FILE__, function() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-linear-activator.php';
		Linear_Activator::activate();
	} );

	/**
	 * Runs during plugin deactivation.
	 */
	register_deactivation_hook( __FILE__, function() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-linear-deactivator.php';
		Linear_Deactivator::deactivate();
	} );

	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-linear.php';

	/**
	 * Utils 
	 */
	require_once plugin_dir_path( __FILE__ ) . "utils/assets.php";

	/**
	 * Gutenberg blocks
	 */
	require_once plugin_dir_path( __FILE__ ) . "blocks/buy-commissions/buy-commissions.php";
	require_once plugin_dir_path( __FILE__ ) . "blocks/listings/listings.php";

	/**
	 * Starts execution of the plugin.
	 */
	function run_linear() {
		$plugin = Linear::get_instance();
		$plugin->run();
	}

	run_linear();
}, 10 );