<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    D3fy_Portfolio
 * @subpackage D3fy_Portfolio/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    D3fy_Portfolio
 * @subpackage D3fy_Portfolio/admin
 * @author     William Cobb <william@d3fy.com>
 */
class D3fy_Portfolio_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The custom post type slug.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $post_type_slug The slug used for the portfolio post type.
	 */
	private $post_type_slug;

	/**
	 * The custom post type label;
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $post_type_label The label used for the portfolio post type.
	 */
	private $post_type_label;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->post_type_slug  = 'd3fy_portfolio';
		$this->post_type_label = 'Portfolio';

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in D3fy_Portfolio_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The D3fy_Portfolio_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/d3fy-portfolio-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in D3fy_Portfolio_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The D3fy_Portfolio_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/d3fy-portfolio-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register portfolio custom post type.
	 *
	 * @since 1.1.0
	 */
	public function register_post_type() {

		$post_type_slug = 'd3fy_portfolio';
		$post_type_label = 'Portfolio';


		$labels = array(
			'name'               => __( $post_type_label, 'd3fy-portfolio' ),
			'singular_name'      => __( $post_type_label . ' Item', 'd3fy-portfolio' ),
			'add_new'            => __( 'Add New Item', 'd3fy-portfolio' ),
			'add_new_item'       => __( 'Add New ' . $post_type_label . ' Item', 'd3fy-portfolio' ),
			'edit_item'          => __( 'Edit ' . $post_type_label . ' Item', 'd3fy-portfolio' ),
			'new_item'           => __( 'Add New ' . $post_type_label . ' Item', 'd3fy-portfolio' ),
			'view_item'          => __( 'View Item', 'd3fy-portfolio' ),
			'search_items'       => __( 'Search ' . $post_type_label . '', 'd3fy-portfolio' ),
			'not_found'          => __( 'No ' . $post_type_label . ' items found', 'd3fy-portfolio' ),
			'not_found_in_trash' => __( 'No ' . $post_type_label . ' items found in trash', 'd3fy-portfolio' ),
		);

		$supports = array(
			'title',
			'editor',
			'thumbnail',
		);

		$args = array(
			'labels'          => $labels,
			'supports'        => $supports,
			'public'          => true,
			'capability_type' => 'post',
			'rewrite'         => array( 'slug' => 'portfolio-item', ),
			'menu_position'   => 5,
			'menu_icon'       => ( version_compare( $GLOBALS['wp_version'], '3.8', '>=' ) ) ? 'dashicons-portfolio' : '',
			'has_archive'     => false,
		);

		$args = apply_filters( 'd3fy_posttype_args', $args );

		register_post_type( $post_type_slug, $args );

	}

	/**
	 * Register taxonomy for portfolio custom post type.
	 *
	 * @since 1.1.0
	 */
	public function register_taxonomy_category() {
		$labels = array(
			'name'                       => __( $this->post_type_label . ' Categories', 'd3fy-portfolio' ),
			'singular_name'              => __( $this->post_type_label . ' Category', 'd3fy-portfolio' ),
			'menu_name'                  => __( $this->post_type_label . ' Categories', 'd3fy-portfolio' ),
			'edit_item'                  => __( 'Edit ' . $this->post_type_label . ' Category', 'd3fy-portfolio' ),
			'update_item'                => __( 'Update ' . $this->post_type_label . ' Category', 'd3fy-portfolio' ),
			'add_new_item'               => __( 'Add New ' . $this->post_type_label . ' Category', 'd3fy-portfolio' ),
			'new_item_name'              => __( 'New ' . $this->post_type_label . ' Category Name', 'd3fy-portfolio' ),
			'parent_item'                => __( 'Parent ' . $this->post_type_label . ' Category', 'd3fy-portfolio' ),
			'parent_item_colon'          => __( 'Parent ' . $this->post_type_label . ' Category:', 'd3fy-portfolio' ),
			'all_items'                  => __( 'All ' . $this->post_type_label . ' Categories', 'd3fy-portfolio' ),
			'search_items'               => __( 'Search ' . $this->post_type_label . ' Categories', 'd3fy-portfolio' ),
			'popular_items'              => __( 'Popular ' . $this->post_type_label . ' Categories', 'd3fy-portfolio' ),
			'separate_items_with_commas' => __( 'Separate Portfolio categories with commas', 'd3fy-portfolio' ),
			'add_or_remove_items'        => __( 'Add or remove Portfolio categories', 'd3fy-portfolio' ),
			'choose_from_most_used'      => __( 'Choose from the most used Portfolio categories', 'd3fy-portfolio' ),
			'not_found'                  => __( 'No Portfolio categories found.', 'd3fy-portfolio' ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_tagcloud'     => true,
			'hierarchical'      => true,
			'rewrite'           => array( 'slug' => 'portfolio-category' ),
			'show_admin_column' => true,
			'query_var'         => true,
		);

		$args = apply_filters( 'd3fy_posttype_category_args', $args );

		register_taxonomy( 'd3fy_portfolio_category', $this->post_type_slug, $args );
	}

}
