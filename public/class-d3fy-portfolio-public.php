<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    D3fy_Portfolio
 * @subpackage D3fy_Portfolio/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    D3fy_Portfolio
 * @subpackage D3fy_Portfolio/public
 * @author     William Cobb <william@d3fy.com>
 */
class D3fy_Portfolio_Public {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/d3fy-portfolio-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name . '-isotope', plugin_dir_url( __FILE__ ) . 'js/vendor/isotope.pkgd.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name . 'd3fy-portfolio-js', plugin_dir_url( __FILE__ ) . 'js/d3fy-portfolio-public.js', array( 'jquery' ), $this->version, true );

	}

	/**
	 * Load custom template for portfolio single view
	 *
	 * @param string $template The template file path to load
	 *
	 * @return string The custom post type single template
	 */
	public function load_portfolio_template( $template ) {

		$plugin_path   = plugin_dir_path( __FILE__ );
		$template_name = 'single-d3fy-portfolio.php';
		$full_path = $plugin_path . 'partials/' . $template_name;

		if ( $template === get_theme_file_uri( $template_name ) || ! file_exists( $full_path ) ) {
			return $template;
		}

		if ( 'd3fy_portfolio' == get_post_type() ) {
			$template = $full_path;
		}

		return $template;
	}

	/**
	 * Setup up the shortcode
	 *
	 * @since 1.1.0
	 */
	public function set_portfolio_shortcode(  ) {
		add_shortcode( 'd3fy-portfolio', array( $this, 'portfolio_shortcode' ) );
	}

	/**
	 * Output the portfolio markup
	 *
	 * @since 1.1.0
	 */
	public function portfolio_shortcode( $atts ) {
		extract( shortcode_atts( array(
			'type'     => 'd3fy_portfolio',
			'all'      => '',
			'showcat'  => '',
			'limit'    => '100'
		), $atts ) );

		$rand_index      = rand();
		$lists               = array();
		$categoryMenu        = array();

		// Filtered category list
		$cat_lists  = array();
		$listHeader = '<div class="grid"><div class="grid-sizer"></div>';
		$listFooter = '</div>';

		$args = array(
			'post_type'      => $type,
			'post_status'    => 'publish',
			'posts_per_page' => $limit,
			'cache_results'  => false,
		);

		$taxonomy   = 'd3fy_portfolio_category';
		$my_query   = null;
		$my_query   = new WP_Query( $args );

		if ( $my_query->have_posts() ) {
			while ( $my_query->have_posts() ) : $my_query->the_post();

				$temp_cat_cmp_array[] = get_the_terms( get_the_ID(), $taxonomy );

				if ( count( $temp_cat_cmp_array ) != 0 && $temp_cat_cmp_array[0] != null ) {
					foreach ( $temp_cat_cmp_array[0] as $cat_cmp ) {
						$cmp_result = false;
						if ( count( $cat_lists ) != 0 ) {
							foreach ( $cat_lists as $cat_val ) {
								if ( $cat_val == $cat_cmp ) {
									$cmp_result = true;
								}
							}
						}
						if ( ! $cmp_result ) {
							array_push( $cat_lists, $cat_cmp );
						}
					}
				}

				usort( $cat_lists, array( $this, 'sortByOrder' ) );

				unset( $temp_cat_cmp_array );
				$temp_cat_cmp_array = array();

				$post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );
				$image             = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
				$title             = get_the_title( $post_thumbnail_id );
				$alt               = get_post_meta( $post_thumbnail_id, '_wp_attachment_image_alt', true );

				if ( $image != '' ) {
					$categories = get_the_terms( get_the_ID(), $taxonomy );
					$cat_class  = '';
					if ( $categories ) {
						foreach ( $categories as $cat ) {
							$cat_class = $cat_class . ' d3fy-' . $cat->slug;
						}
					}

					$image_code = '<img src="' . $image[0] . '" style="width:100%" title="' . $title . '" alt="' . $alt . '" >';

					$list = '<div class="d3fy-item ' . $cat_class . '"  data-category="' . $cat_class . '">
                          <ul class="caption-style-1">
                            <li>
                              ' . $image_code . '
                              <a href="' . get_post_permalink() . '" class="caption">
                                <div class="blur"></div>
                                <div class="caption-text">
                                  <p>' . get_the_title() . '</p>
                                </div>
                              </a>
                            </li>
                          </ul>
                        </div>';

					array_push( $lists, $list );
				}
			endwhile;
		}
		wp_reset_query();

		if ( count( $cat_lists ) != 0 ) {
			$paramCustom = array(
				'all'          => $all,
				'initialClass' => $cat_lists[0]->slug,
			);
		} else {
			$paramCustom = array(
				'all'          => '1',
				'initialClass' => '0',
			);
		}

		if ( $all == 'true' && count( $cat_lists ) != 0 ) {
			$categoryMenu[] = '<li><a class="d3fy-button current d3fy-button-' . $rand_index . '" data-filter="*">All</a></li>';
		}

		if ( count( $cat_lists ) != 0 ) {
			foreach ( $cat_lists as $term_cat ) {
				$categoryMenu[] = '<li><a class="d3fy-button" data-filter=".d3fy-' . $term_cat->slug . '">' . ucfirst( $term_cat->name ) . '</a></li>';
			}
		}

		wp_localize_script( $this->plugin_name . 'd3fy-portfolio-js', 'pluginSetting', $paramCustom );

		if ( $showcat == 'true' ) {
			$catMenu = '<div class="portfolio-filters-inline"><ul style="margin-left: 30px;">' . implode( '', $categoryMenu ) . '</ul></div>';
		} else {
			$catMenu = '';
		}

		return '' . $catMenu . '' . $listHeader . '' . implode( '', $lists ) . '' . $listFooter . '';
	}

	/**
	 * Sort portfolio pieces
	 *
	 * @param $a
	 * @param $b
	 *
	 * @return int
	 */
	public function sortByOrder( $a, $b ) {
		return strcmp( $a->slug, $b->slug );
	}

}
