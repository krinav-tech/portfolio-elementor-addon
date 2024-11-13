<?php

namespace PORTFOLIO_PLUGIN\Inc;

use PORTFOLIO_PLUGIN\Inc\Traits\Singleton;

class Test{

    use singleton;

    protected function __construct()
    {
        // $this->setup_hooks();

        echo 'working or not';
    }

    protected function setup_hooks(){

        add_action( 'wp_enqueue_scripts', [$this, 'register_test_script'] );
        add_action( 'wp_enqueue_scripts', [$this, 'enqueue_test_script'] );
    }

    public function register_test_script(){

        wp_register_script( 'test-script', PORTFOLIO_PLUGIN_URL . '/assets/js/test.js',);

    }

    public function enqueue_test_script() {
        // Enqueue the Color Thief library

        wp_enqueue_script( 'test-script', PORTFOLIO_PLUGIN_URL . '/assets/js/test.js',);

      }



}

?>