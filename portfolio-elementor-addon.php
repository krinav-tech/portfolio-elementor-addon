<?php
/*
 * Plugin Name:       Portfolio Elementor Addon
 * Description:       Custom Elementor widget for displaying portfolio post.
 * Version:           1.2.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Krinav Chaudhari
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * Text Domain:       portfolio-plugin
 */

 if ( ! defined( 'PORTFOLIO_PLUGIN_DIR_PATH' ) ) {
    define( 'PORTFOLIO_PLUGIN_DIR_PATH', untrailingslashit( plugin_dir_path( __FILE__  ) ) );
}

 if ( ! defined( 'PORTFOLIO_PLUGIN_URL' ) ) {
    define( 'PORTFOLIO_PLUGIN_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
}

require_once PORTFOLIO_PLUGIN_DIR_PATH ."/inc/helpers/autoloader.php";

function portfolio_plugin_instance(){

    \PORTFOLIO_PLUGIN\Inc\PORTFOLIO_ELEMENTOR_ADDON_PLUGIN::get_instance();
}
portfolio_plugin_instance();
