<?php
/**
 * Load the assets
 *
 * @package portfolio-plugin
 */

namespace PORTFOLIO_PLUGIN\Inc;

use PORTFOLIO_PLUGIN\Inc\Traits\singleton;

class Assets {

    use singleton;

    protected function __construct() {

        $this->setup_hooks();

    }

       public function setup_hooks() {

        /** Actions */

        // Register scripts and style

        add_action( 'wp_enqueue_scripts', [$this, 'register_plugin_scripts'] );
        add_action( 'wp_enqueue_scripts', [$this, 'register_plugin_styles'] );

        // enqueue script

        add_action( 'wp_enqueue_scripts', [$this, 'enqueue_color_thief_script'] );
        add_action( 'wp_enqueue_scripts', [$this, 'enqueue_portfolio_scripts'] );

        // enqueue style
        add_action( 'wp_enqueue_scripts', [$this, 'enqueue_portfolio_styles'] );

    }

    // Register scripts
    public function register_plugin_scripts() {
      wp_register_script( 'color-thief', 'https://cdnjs.cloudflare.com/ajax/libs/color-thief/2.3.0/color-thief.min.js', [], '2.3.0', true );
      wp_register_script( 'custom-color-script', PORTFOLIO_PLUGIN_URL . '/assets/js/custom-color.js', ['color-thief'], '1.0.0', true );
      wp_register_script( 'portfolio-main-js', PORTFOLIO_PLUGIN_URL . '/assets/js/main.js', [], false, true );

    }

    // Register styles
    public function register_plugin_styles() {
      wp_register_style( 'portfolio-css', PORTFOLIO_PLUGIN_URL . '/assets/css/portfolio.css' );
    }

    public function enqueue_color_thief_script() {
        // Enqueue the Color Thief library
        wp_enqueue_script( 'color-thief', 'https://cdnjs.cloudflare.com/ajax/libs/color-thief/2.3.0/color-thief.min.js', [], '2.3.0', true );
        wp_enqueue_script( 'custom-color-script', PORTFOLIO_PLUGIN_URL . '/assets/js/custom-color.js', ['color-thief'], '1.0.0', true );

      }

    public function enqueue_portfolio_scripts() {

        wp_enqueue_script( 'portfolio-main-js', PORTFOLIO_PLUGIN_URL . '/assets/js/main.js', [], false, true );

        $sector_field_key   = "field_65d49037d3c1f";
        $sector_field       = get_field_object( $sector_field_key );
        $sector_choices     = $sector_field ? $sector_field['choices'] : [];

        $region_field_key   = "field_65d491dfe3614";
        $region_field       = get_field_object( $region_field_key );
        $region_choices     = $region_field ? $region_field['choices'] : [];

        $status_field_key   = "field_65d4909fd3c21";
        $status_field       = get_field_object( $status_field_key );
        $status_choices     = $status_field ? $status_field['choices'] : [];

        // Combine data into one array
        $localization_data = [
            'sectorData' => $sector_choices,
            'regionData' => $region_choices,
            'statusData' => $status_choices
        ];

        // Localize script with combined data
        wp_localize_script( 'portfolio-main-js', 'portfolioData', $localization_data );
    }

    public function enqueue_portfolio_styles() {

        wp_enqueue_style( 'portfolio-css', PORTFOLIO_PLUGIN_URL . '/assets/css/portfolio.css' );

    }
}
