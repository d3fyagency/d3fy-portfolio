<?php
/**
 * @link              http://example.com
 * @since             1.0.0
 * @package           D3fy_Portfolio
 *
 * @wordpress-plugin
 * Plugin Name:       D3FY Portfolio
 * Plugin URI:        http://example.com/d3fy-portfolio-uri/
 * Description:       Adds a Portfolio Post Type with grid display and category filtering..
 * Version:           1.1.0
 * Author:            D3FY Development
 * Author URI:        https://www.d3fy.com/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       d3fy-portfolio
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-d3fy-portfolio-activator.php
 */
function activate_d3fy_portfolio() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-d3fy-portfolio-activator.php';
	D3fy_Portfolio_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-d3fy-portfolio-deactivator.php
 */
function deactivate_d3fy_portfolio() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-d3fy-portfolio-deactivator.php';
	D3fy_Portfolio_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_d3fy_portfolio' );
register_deactivation_hook( __FILE__, 'deactivate_d3fy_portfolio' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-d3fy-portfolio.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_d3fy_portfolio() {

	$plugin = new D3fy_Portfolio();
	$plugin->run();

}
run_d3fy_portfolio();
