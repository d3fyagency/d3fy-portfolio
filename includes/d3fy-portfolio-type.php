<?php
/**
 * D3FY Portfolio Post Type
 *
 * @package   d3fy_portfolio
 * @author    D3FY Development
 * @license   GPL-3.0+
 * @copyright 2016 D3FY Development
 */

class D3FY_Portfolio_Type {

	const VERSION = '1.0.0';
	const PLUGIN_SLUG = 'd3fy-portfolio';

	protected $registration_handler;

	public function __construct( $registration_handler ) {

		$this->registration_handler = $registration_handler;
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

	}

	public function activate( $network_wide ) {
        add_option( D3FY_PORTFOLIO_VERSION_SLUG, D3FY_PORTFOLIO_VERSION );
        
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			if ( $network_wide  ) {
				$blog_ids = $this->get_blog_ids();
				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					$this->single_activate();
				}
				restore_current_blog();
			} else {
				$this->single_activate();
			}
		} else {
			$this->single_activate();
		}
	}

	public function deactivate( $network_wide ) {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			if ( $network_wide ) {
				$blog_ids = $this->get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					$this->single_deactivate();
				}
				restore_current_blog();
			} else {
				$this->single_deactivate();
			}
		} else {
			$this->single_deactivate();
		}
	}

	public function activate_new_site( $blog_id ) {
		if ( 1 !== did_action( 'wpmu_new_blog' ) )
			return;

		switch_to_blog( $blog_id );
		$this->single_activate();
		restore_current_blog();
	}

	private function get_blog_ids() {
		global $wpdb;
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";
		return $wpdb->get_col( $sql );
	}

	private function single_activate() {
		$this->registration_handler->register();
		flush_rewrite_rules();
	}

	private function single_deactivate() {
		flush_rewrite_rules();
	}

	public function load_plugin_textdomain() {
		$domain = self::PLUGIN_SLUG;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages' );
	}

}
