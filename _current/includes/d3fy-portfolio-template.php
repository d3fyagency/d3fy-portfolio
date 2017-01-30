<?php
/**
 * D3FY Portfolio Template.
 *
 * @author    D3FY Development
 * @license   GPL-3.0+
 * @copyright 2016 D3FY Development
 */
function load_d3fy_portfolio_template($template)
{
    global $post;

    if ($post->post_type == 'd3fy_portfolio') {
        $plugin_path = plugin_dir_path(__FILE__);
        $template_name = 'single-d3fy-portfolio.php';

        if ($template === get_stylesheet_directory().'/'.$template_name
            || !file_exists($plugin_path.$template_name)) {
            return $template;
        }

        return $plugin_path.$template_name;
    }

    return $template;
}
add_filter('single_template', 'load_d3fy_portfolio_template');
