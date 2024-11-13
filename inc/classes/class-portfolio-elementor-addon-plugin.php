<?php
/**
 *
 * bootsraps the plugin
 *
 * @pakage portfolio-plugin
 *
 */

namespace PORTFOLIO_PLUGIN\Inc;

use PORTFOLIO_PLUGIN\Inc\Traits\singleton;

 class PORTFOLIO_ELEMENTOR_ADDON_PLUGIN{

    use singleton;

    protected function __construct()
    {

        $this->setup_hooks();

        // Test::get_instance();
        Assets::get_instance();
        Filtered_Posts::get_instance();

     }

     protected function setup_hooks(){

        // Register the new widget category

        add_action( 'elementor/elements/categories_registered', function ( $elements_manager ) {
             $elements_manager->add_category(
                'vts',
                [
                    'title' => __('VTS', 'portfolio-plugin'),
                    'icon' => 'fa fa-plug',
                ]
            );

            error_log( 'VTS category registered' );
        });

        // Register the widgets

        add_action('elementor/widgets/widgets_registered', function ($widgets_manager) {

            require_once( PORTFOLIO_PLUGIN_DIR_PATH . '/inc/widgets/portfolio-widget.php');

            $widgets_manager->register(new \PORTFOLIO_PLUGIN\Inc\Widgets\Portfolio_Widget());

        });

     }

 }