<?php
/**
 * Plugin Name: D3FY Portfolio
 * Description: Adds a Portfolio Post Type with grid display and category filtering
 * Version:     1.0.1
 * Author:      D3FY Development
 * Author URI:  https://www.d3fy.com/
 * Text Domain: d3fy-portfolio
 * License:     GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages.
 */
if (!defined('WPINC')) {
    die;
}

define('D3FY_PORTFOLIO_VERSION', '1.0');
define('D3FY_PORTFOLIO_VERSION_SLUG', 'd3fy_portfolio_version');
define('D3FY_PORTFOLIO_TEXT_DOMAIN', 'd3fy-portfolio');
define('D3FY_PORTFOLIO_PLGUINURI', plugin_dir_path(__FILE__));
define('D3FY_PORTFOLIO_PLUGINURL', plugins_url('', __FILE__));

require_once D3FY_PORTFOLIO_PLGUINURI.'includes/d3fy-portfolio-type.php';
require_once D3FY_PORTFOLIO_PLGUINURI.'includes/d3fy-portfolio-post-type-registrations.php';
require_once D3FY_PORTFOLIO_PLGUINURI.'includes/d3fy-portfolio-template.php';
require_once D3FY_PORTFOLIO_PLGUINURI.'includes/d3fy-portfolio-meta.php';

$portfolio_post_type_registrations = new Portfolio_Post_Type_Registrations();
$d3fy_portfolio = new D3FY_Portfolio_Type($portfolio_post_type_registrations);
register_activation_hook(__FILE__, array($d3fy_portfolio, 'activate'));
$portfolio_post_type_registrations->init();

class D3FY_Portfolio_Plugin
{
    private $meta_prefix = '_d3fy_portfolio_';
    private $post_type = 'd3fy_portfolio';

    public function __construct()
    {
        add_action('init', array($this, 'd3fy_portfolio_init'));
    }

    public function d3fy_portfolio_init()
    {
        add_shortcode('d3fy', array($this, 'd3fy_portfolio_func'));
        add_action('wp_enqueue_scripts', array($this, 'load_frontend_libraries'));
        add_filter('shortcode_atts_gallery', array($this, 'd3fy_portfolio_gallery_atts'), 10, 3);
        add_action('admin_enqueue_scripts', array($this, 'd3fy_portfolio_admin_enqueue'));
    }

    public function d3fy_portfolio_func($atts)
    {
        extract(shortcode_atts(array(
            'featured' => '',
            'all' => '',
            'showcat' => '', ),
            $atts));

        return $this->Building_Portfolio_List($featured, $all, $showcat);
    }
    public function load_frontend_libraries()
    {
        wp_enqueue_style('d3fy-portfolio-default-style',
          plugins_url('assets/css/d3fy-portfolio-style.css', __FILE__));

        wp_enqueue_script(
          'd3fy-portfolio-masonry-script',
          plugins_url('assets/js/isotope.pkgd.min.js', __FILE__), array('jquery'), D3FY_PORTFOLIO_VERSION, true
      );

        wp_register_script('d3fy-portfolio-custom-script',
          plugins_url('assets/js/custom.js', __FILE__), array('jquery'), D3FY_PORTFOLIO_VERSION, true);

        wp_localize_script('pluginSetting', $params);
        wp_enqueue_script('d3fy-portfolio-custom-script');
    }

    public function sortByOrder($a, $b)
    {
        return strcmp($a->slug, $b->slug);
    }

    public function Building_Portfolio_List($featured, $all, $showcat)
    {
        $disableClicking = '';
        $rand_index = rand();

        $d3fy_portfolio_meta = array();
        $lists = array();
        $paramCustom = array();
        $categoryMenu = array();
      // Filtered category list
        $cat_lists = array();
        $type = 'd3fy_portfolio';
        $listHeader = '<div class="grid"><div class="grid-sizer"></div>';
        $listFooter = '</div>';

        $i = 1;

        if ($featured == 'true') {
            $args = array(
              'post_type' => $type,
              'post_status' => 'publish',
              'posts_per_page' => -1,
              'meta_query' => array(
                                  array(
                                  'key' => 'featured-checkbox',
                                  'value' => 'yes',
                          ),
                      ),
              'cache_results' => false, );
        } else {
            $args = array(
              'post_type' => $type,
              'post_status' => 'publish',
              'posts_per_page' => -1,
              'cache_results' => false, );
        }

        $taxonomy = 'd3fy_portfolio_category';
        $image_code = '';
        $my_query = null;
        $my_query = new WP_Query($args);

        if ($my_query->have_posts()) {
            while ($my_query->have_posts()) : $my_query->the_post();

            $i = rand();

            $d3fy_portfolio_meta = get_post_meta(get_the_ID());

            $temp_cat_cmp_array[] = get_the_terms(get_the_ID(), $taxonomy);

            if (count($temp_cat_cmp_array) != 0 && $temp_cat_cmp_array[0] != null) {
                foreach ($temp_cat_cmp_array[0] as $cat_cmp) {
                    $cmp_result = false;
                    if (count($cat_lists) != 0) {
                        foreach ($cat_lists as $cat_val) {
                            if ($cat_val == $cat_cmp) {
                                $cmp_result = true;
                            }
                        }
                    }
                    if (!$cmp_result) {
                        array_push($cat_lists, $cat_cmp);
                    }
                }
            }

            usort($cat_lists, array($this, 'sortByOrder'));

            unset($temp_cat_cmp_array);
            $temp_cat_cmp_array = array();

            $post_thumbnail_id = get_post_thumbnail_id(get_the_ID());
            $image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full');
            $title = get_the_title($post_thumbnail_id);
            $alt = get_post_meta($post_thumbnail_id, '_wp_attachment_image_alt', true);

            if ($image != '') {
                $categories = get_the_terms(get_the_ID(), $taxonomy);
                $cat_class = '';
                if ($categories) {
                    foreach ($categories as $cat) {
                        $cat_class = $cat_class.' d3fy-'.$cat->slug;
                    }
                }

                $image_code = '<img src="'.$image[0].'" style="width:100%" title="'.$title.'" alt="'.$alt.'" >';

                $list = '<div class="d3fy-item '.$cat_class.'"  data-category="'.$cat_class.'">
                          <ul class="caption-style-1">
                            <li>
                              '.$image_code.'
                              <a href="'.get_post_permalink().'" class="caption">
                                <div class="blur"></div>
                                <div class="caption-text">
                                  <p>'.get_the_title().'</p>
                                </div>
                              </a>
                            </li>
                          </ul>
                        </div>';

                array_push($lists, $list);
                ++$i;
            }
            endwhile;
        }
        wp_reset_query();

        if (count($cat_lists) != 0) {
            $paramCustom = array('all' => $all,
          'initialClass' => $cat_lists[0]->slug, );
        } else {
            $paramCustom = array('all' => '1',
          'initialClass' => '0', );
        }

        if ($all == 'true' && count($cat_lists) != 0) {
            $categoryMenu[] = '<li><a class="d3fy-button current d3fy-button-'.$rand_index.'" data-filter="*">All</a></li>';
        }

        if (count($cat_lists) != 0) {
            foreach ($cat_lists as $term_cat) {
                $categoryMenu[] = '<li><a class="d3fy-button" data-filter=".d3fy-'.$term_cat->slug.'">'.ucfirst($term_cat->name).'</a></li>';
            }
        }

        wp_localize_script('d3fy-portfolio-custom-script', 'pluginSetting', $paramCustom);

        if ($showcat == 'true') {
            $catMenu = '<div class="portfolio-filters-inline"><ul style="margin-left: 30px;">'.implode('', $categoryMenu).'</ul></div>';
        } else {
            $catMenu = '';
        }

        return ''.$catMenu.''.$listHeader.''.implode('', $lists).''.$listFooter.'';
    }
}

$d3fy_portfolio_plugin = new D3FY_Portfolio_Plugin();
