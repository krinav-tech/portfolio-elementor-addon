<?php

namespace PORTFOLIO_PLUGIN\Inc\Traits;

trait Controls_Trait {

    public function control_widget() {

        $this->start_controls_section(
            'layout_section',
            [
                'label' => __('Layout', 'portfolio-plugin'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => __('Posts Per Page', 'portfolio-plugin'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 100,
                'step' => 1,
                'default' => 10,
                'description' => 'Control the number of posts displayed per page.',
            ]
        );

        $this->add_responsive_control(
            'items_per_row',
            [
                'label' => __('Items Per Row', 'portfolio-plugin'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'step' => 1,
                'default' => 4,
                'selectors' => [
                    '{{WRAPPER}} .row-flex' => 'flex-wrap: wrap;',
                    '{{WRAPPER}} .portfolio-list-item' => 'width: calc(100% / {{VALUE}} - 20px);', // Adjust for margins or gaps
                ],
                'frontend_available' => true,
            ]
        );


        // Flex direction
        $this->add_control(
            'flex_direction',
            [
                'label' => __('Flex Direction', 'portfolio-plugin'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'row' => __('Row', 'portfolio-plugin'),
                    'column' => __('Column', 'portfolio-plugin'),
                ],
                'default' => 'row',
                'selectors' => [
                    '{{WRAPPER}} .row-flex' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );

        // Justify Content control for .row-flex
        $this->add_responsive_control(
            'justify_content',
            [
                'label' => __('Justify Content', 'portfolio-plugin'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => __('Start', 'portfolio-plugin'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'portfolio-plugin'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => __('End', 'portfolio-plugin'),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'space-between' => [
                        'title' => __('Space Between', 'portfolio-plugin'),
                        'icon' => 'eicon-h-align-stretch',
                    ],
                    'space-around' => [
                        'title' => __('Space Around', 'portfolio-plugin'),
                        'icon' => 'eicon-h-align-stretch',
                    ],
                ],
                'default' => 'flex-start',
                'selectors' => [
                    '{{WRAPPER}} .row-flex' => 'justify-content: {{VALUE}};',
                ],
            ]
        );



        $post_types = get_post_types(['public' => true], 'objects');
        $post_type_options = [];
        foreach ($post_types as $post_type) {
            $post_type_options[$post_type->name] = $post_type->label;
        }

        $this->add_control(
            'selected_post_type',
            [
                'label' => __('Post Type', 'portfolio-plugin'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $post_type_options,
                'default' => 'post',
            ]
        );

        $template_options = $this->get_elementor_templates();

        $this->add_control(
            'elementor_template',
            [
                'label' => __('alternate template', 'portfolio-plugin'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $template_options,
                'default' => array_key_first($template_options),
            ]
        );

        $this->add_control(
            'alternate_template_interval',
            [
                'label' => __('Alternate Template Interval', 'portfolio-plugin'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 100,
                'step' => 1,
                'default' => 8,
                'description' => 'Display an alternate template after this number of posts.',
            ]
        );

        // Alignment control using Flexbox
        $this->add_control(
            'display_type',
            [
                'label' => __('Display Type', 'portfolio-plugin'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'block' => __('Block', 'portfolio-plugin'),
                    'flex' => __('Flex', 'portfolio-plugin')
                ],
                'default' => 'flex',
                'frontend_available' => true,
            ]
        );

        $this->add_responsive_control(
            'flex_justify_content',
            [
                'label' => __('Justify Content', 'portfolio-plugin'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => __('Start', 'portfolio-plugin'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'portfolio-plugin'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => __('End', 'portfolio-plugin'),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'space-between' => [
                        'title' => __('Space Between', 'portfolio-plugin'),
                        'icon' => 'eicon-h-align-stretch',
                    ],
                    'space-around' => [
                        'title' => __('Space Around', 'portfolio-plugin'),
                        'icon' => 'eicon-h-align-stretch',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .template-wrapper' => 'justify-content: {{VALUE}};',
                ],
                'condition' => [
                    'display_type' => 'flex'
                ],
            ]
        );

        $this->add_responsive_control(
            'width',
            [
                'label' => __('Width', 'portfolio-plugin'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['%', 'px', 'vw'],
                'range' => [
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'px' => [
                        'min' => 100,
                        'max' => 1920,
                        'step' => 10,
                    ],
                    'vw' => [
                        'min' => 10,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .template-wrapper' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'margin',
            [
                'label' => __('Margin', 'portfolio-plugin'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .template-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'padding',
            [
                'label' => __('Padding', 'portfolio-plugin'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .template-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );




        $this->end_controls_section();

        // new section or add to an existing section
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Style Settings', 'portfolio-plugin'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'content_width',
            [
                'label' => __('Content Width', 'portfolio-plugin'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['%', 'px', 'vw'], // Allows percentage, pixels, and viewport widths
                'range' => [
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'px' => [
                        'min' => 100,
                        'max' => 1920,
                        'step' => 10,
                    ],
                    'vw' => [
                        'min' => 10,
                        'max' => 100,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .portfolio-company-card' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Typography and color for the entire card
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'label' => __('Content Typography', 'portfolio-plugin'),
                'selector' => '{{WRAPPER}} .portfolio-company-card',
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => __('Content Color', 'portfolio-plugin'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .portfolio-company-card' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Specific typography and color for the portfolio card name
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography',
                'label' => __('Name Typography', 'portfolio-plugin'),
                'selector' => '{{WRAPPER}} .portfolio-card-name',
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label' => __('Name Color', 'portfolio-plugin'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .portfolio-card-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_card',
            [
                'label' => __('Company Card Style', 'portfolio-plugin'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Background color control
        $this->add_control(
            'card_background_color',
            [
                'label' => __('Background Color', 'portfolio-plugin'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .portfolio-list-item' => 'background-color: {{VALUE}};', // Applies the background color
                ],
            ]
        );

        // Border control
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'card_border',
                'label' => __('Border', 'portfolio-plugin'),
                'selector' => '{{WRAPPER}} .portfolio-list-item',
            ]
        );

        // Border radius control
        $this->add_control(
            'card_border_radius',
            [
                'label' => __('Border Radius', 'portfolio-plugin'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .portfolio-list-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'labels_style',
            [
                'label' => __('Labels Style', 'portfolio-plugin'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Typography control for label
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'label' => __('Label Typography', 'portfolio-plugin'),
                'selector' => '{{WRAPPER}} .portfolio-card-labal',
            ]
        );

        // Color control for label
        $this->add_control(
            'label_color',
            [
                'label' => __('Label Color', 'portfolio-plugin'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .portfolio-card-labal' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'hover_effects',
            [
                'label' => __('Hover Effects', 'portfolio-plugin'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Toggle to enable custom flex-basis on hover
        $this->add_control(
            'enable_custom_flex_basis',
            [
                'label' => __('Enable Custom Hover Size', 'portfolio-plugin'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'portfolio-plugin'),
                'label_off' => __('No', 'portfolio-plugin'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        // Slider for custom flex-basis on hover
        $this->add_control(
            'hover_flex_basis',
            [
                'label' => __('Flex Basis on Hover', 'portfolio-plugin'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 20,
                        'max' => 35,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 25,
                ],
                'selectors' => [
                    '{{WRAPPER}} .portfolio-list-item:hover' => 'flex-basis: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'enable_custom_flex_basis' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'result_style_section', // Ensure this section ID is unique and descriptive
            [
                'label' => __('Result Count Typography', 'portfolio-plugin'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Adding typography control for .result-count
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'result_count_typography', // Unique identifier for your control
                'label' => __('Result Count Typography', 'portfolio-plugin'),
                'selector' => '{{WRAPPER}} .result-count', // CSS selector to target
            ]
        );

        $this->end_controls_section();
    }


}
