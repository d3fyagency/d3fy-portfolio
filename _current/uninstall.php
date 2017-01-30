<?php

// If uninstall not called from WordPress, then exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

if (!current_user_can('activate_plugins')) {
    exit;
}

// Define uninstall functionality here
$post_type = 'd3fy_portfolio';
global $wp_post_types;
if (isset($wp_post_types[ $post_type ])) {
    unset($wp_post_types[ $post_type ]);

    return true;
}

flush_rewrite_rules();
