<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    D3fy_Portfolio
 * @subpackage D3fy_Portfolio/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    D3fy_Portfolio
 * @subpackage D3fy_Portfolio/includes
 * @author     William Cobb <william@d3fy.com>
 */
class D3fy_Portfolio_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		if ( is_multisite() ) {

			if ( wp_is_large_network() ) {
				return;
			}

			$sites = get_sites( array( 'number' => 1000 ) );
			foreach ( $sites as $site ) {
				switch_to_blog( $site->blog_id );
				delete_option( 'rewrite_rules' );
				restore_current_blog();
			}

		} else {
			flush_rewrite_rules();
		}

	}

}
