<?php

namespace PORTFOLIO_PLUGIN\Inc\Widgets;

use PORTFOLIO_PLUGIN\Inc\Traits\Render_Trait;
use PORTFOLIO_PLUGIN\Inc\Traits\Controls_Trait;

class Portfolio_Widget extends \Elementor\Widget_Base {

    use Render_Trait;
    use Controls_Trait;

    public function get_name() {

        return 'Portfolio_Widget';
    }

    public function get_categories() {

        return ['vts'];
    }

    public function get_title() {

        return __('VTS Portfolio', 'portfolio-plugin');
    }

    public function get_icon() {
        return 'eicon-thumbnails-half';
    }

    private function get_elementor_templates() {
        $templates = get_posts([
            'post_type' => 'elementor_library',
            'numberposts' => -1,  // Retrieve all templates
        ]);

        $options = [];
        if (!empty($templates)) {
            foreach ($templates as $template) {
                $options[$template->ID] = $template->post_title;
            }
        }

        return $options;
    }


    protected function _register_controls() {

        $this->control_widget();
    }

    protected function render(){

        $this->render_widget();

    }
}
