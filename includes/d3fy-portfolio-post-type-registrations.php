<?php

class Portfolio_Post_Type_Registrations {

  public $post_type = 'd3fy_portfolio';
  public $c_post_type = 'Portfolio';

	public $taxonomies = array( 'd3fy_portfolio_category', 'd3fy_portfolio_tag' );

	public function init() {
		add_action( 'init', array( $this, 'register' ) );
	}

	public function register() {
		$this->register_post_type();
    $this->register_taxonomy_category();
	}

	protected function register_post_type() {
		$labels = array(
			'name'               => __( $this->c_post_type, 'd3fy-portfolio' ),
			'singular_name'      => __( $this->c_post_type.' Item', 'd3fy-portfolio' ),
			'add_new'            => __( 'Add New Item', 'd3fy-portfolio' ),
			'add_new_item'       => __( 'Add New '.$this->c_post_type.' Item', 'd3fy-portfolio' ),
			'edit_item'          => __( 'Edit '.$this->c_post_type.' Item', 'd3fy-portfolio' ),
			'new_item'           => __( 'Add New '.$this->c_post_type.' Item', 'd3fy-portfolio' ),
			'view_item'          => __( 'View Item', 'd3fy-portfolio' ),
			'search_items'       => __( 'Search '.$this->c_post_type.'', 'd3fy-portfolio' ),
			'not_found'          => __( 'No '.$this->c_post_type.' items found', 'd3fy-portfolio' ),
			'not_found_in_trash' => __( 'No '.$this->c_post_type.' items found in trash', 'd3fy-portfolio' ),
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

		register_post_type( $this->post_type, $args );
	}

	protected function register_taxonomy_category() {
		$labels = array(
			'name'                       => __( $this->c_post_type.' Categories', 'd3fy-portfolio' ),
			'singular_name'              => __( $this->c_post_type.' Category', 'd3fy-portfolio' ),
			'menu_name'                  => __( $this->c_post_type.' Categories', 'd3fy-portfolio' ),
			'edit_item'                  => __( 'Edit '.$this->c_post_type.' Category', 'd3fy-portfolio' ),
			'update_item'                => __( 'Update '.$this->c_post_type.' Category', 'd3fy-portfolio' ),
			'add_new_item'               => __( 'Add New '.$this->c_post_type.' Category', 'd3fy-portfolio' ),
			'new_item_name'              => __( 'New '.$this->c_post_type.' Category Name', 'd3fy-portfolio' ),
			'parent_item'                => __( 'Parent '.$this->c_post_type.' Category', 'd3fy-portfolio' ),
			'parent_item_colon'          => __( 'Parent '.$this->c_post_type.' Category:', 'd3fy-portfolio' ),
			'all_items'                  => __( 'All '.$this->c_post_type.' Categories', 'd3fy-portfolio' ),
			'search_items'               => __( 'Search '.$this->c_post_type.' Categories', 'd3fy-portfolio' ),
			'popular_items'              => __( 'Popular '.$this->c_post_type.' Categories', 'd3fy-portfolio' ),
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

		register_taxonomy( $this->taxonomies[0], $this->post_type, $args );
	}
}
