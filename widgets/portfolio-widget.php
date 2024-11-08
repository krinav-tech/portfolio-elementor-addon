<?php
class Portfolio_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'Portfolio_Widget';
    }

    public function get_categories()
    {
        return ['vts'];
    }

    public function get_title()
    {
        return __('VTS Portfolio', 'text-domain');
    }

    public function get_icon()
    {
        return 'eicon-thumbnails-half';
    }

    private function get_elementor_templates()
    {
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


    protected function _register_controls()
    {

        $this->start_controls_section(
            'layout_section',
            [
                'label' => __('Layout', 'text-domain'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => __('Posts Per Page', 'text-domain'),
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
                'label' => __('Items Per Row', 'text-domain'),
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
                'label' => __('Flex Direction', 'text-domain'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'row' => __('Row', 'text-domain'),
                    'column' => __('Column', 'text-domain'),
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
                'label' => __('Justify Content', 'text-domain'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => __('Start', 'text-domain'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'text-domain'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => __('End', 'text-domain'),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'space-between' => [
                        'title' => __('Space Between', 'text-domain'),
                        'icon' => 'eicon-h-align-stretch',
                    ],
                    'space-around' => [
                        'title' => __('Space Around', 'text-domain'),
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
                'label' => __('Post Type', 'text-domain'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $post_type_options,
                'default' => 'post',
            ]
        );

        $template_options = $this->get_elementor_templates();

        $this->add_control(
            'elementor_template',
            [
                'label' => __('alternate template', 'text-domain'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $template_options,
                'default' => array_key_first($template_options),
            ]
        );

        $this->add_control(
            'alternate_template_interval',
            [
                'label' => __('Alternate Template Interval', 'text-domain'),
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
                'label' => __('Display Type', 'text-domain'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'block' => __('Block', 'text-domain'),
                    'flex' => __('Flex', 'text-domain')
                ],
                'default' => 'flex',
                'frontend_available' => true,
            ]
        );

        $this->add_responsive_control(
            'flex_justify_content',
            [
                'label' => __('Justify Content', 'text-domain'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => __('Start', 'text-domain'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'text-domain'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => __('End', 'text-domain'),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'space-between' => [
                        'title' => __('Space Between', 'text-domain'),
                        'icon' => 'eicon-h-align-stretch',
                    ],
                    'space-around' => [
                        'title' => __('Space Around', 'text-domain'),
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
                'label' => __('Width', 'text-domain'),
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
                'label' => __('Margin', 'text-domain'),
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
                'label' => __('Padding', 'text-domain'),
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
                'label' => __('Style Settings', 'text-domain'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'content_width',
            [
                'label' => __('Content Width', 'text-domain'),
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
                'label' => __('Content Typography', 'text-domain'),
                'selector' => '{{WRAPPER}} .portfolio-company-card',
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => __('Content Color', 'text-domain'),
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
                'label' => __('Name Typography', 'text-domain'),
                'selector' => '{{WRAPPER}} .portfolio-card-name',
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label' => __('Name Color', 'text-domain'),
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
                'label' => __('Company Card Style', 'text-domain'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Background color control
        $this->add_control(
            'card_background_color',
            [
                'label' => __('Background Color', 'text-domain'),
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
                'label' => __('Border', 'text-domain'),
                'selector' => '{{WRAPPER}} .portfolio-list-item',
            ]
        );

        // Border radius control
        $this->add_control(
            'card_border_radius',
            [
                'label' => __('Border Radius', 'text-domain'),
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
                'label' => __('Labels Style', 'text-domain'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Typography control for label
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'label' => __('Label Typography', 'text-domain'),
                'selector' => '{{WRAPPER}} .portfolio-card-labal',
            ]
        );

        // Color control for label
        $this->add_control(
            'label_color',
            [
                'label' => __('Label Color', 'text-domain'),
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
                'label' => __('Hover Effects', 'text-domain'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Toggle to enable custom flex-basis on hover
        $this->add_control(
            'enable_custom_flex_basis',
            [
                'label' => __('Enable Custom Hover Size', 'text-domain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'text-domain'),
                'label_off' => __('No', 'text-domain'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        // Slider for custom flex-basis on hover
        $this->add_control(
            'hover_flex_basis',
            [
                'label' => __('Flex Basis on Hover', 'text-domain'),
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
                'label' => __('Result Count Typography', 'text-domain'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        // Adding typography control for .result-count
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'result_count_typography', // Unique identifier for your control
                'label' => __('Result Count Typography', 'text-domain'),
                'selector' => '{{WRAPPER}} .result-count', // CSS selector to target
            ]
        );

        $this->end_controls_section();
    }


    protected function render()
    {

        $settings = $this->get_settings_for_display();
        $selected_post_type = $settings['selected_post_type'];



        global $wpdb;



        // Define the number of posts per page
        // $posts_per_page = ($total_posts > 0) ? $total_posts : 3;
        $posts_per_page = $settings['posts_per_page'];

        // Get the current page number from the query string (default to 1 if not present)
        $url_parts = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));
        $paged = is_numeric(end($url_parts)) ? (int)end($url_parts) : 1;

        // Calculate the offset for the current page
        $offset = ($paged - 1) * $posts_per_page;

        $args = array(
            'post_type'      => $selected_post_type,
            'posts_per_page' => $posts_per_page,
            'offset'         => $offset,
            'orderby'        => 'date',
            'order'          => 'DESC',
        );

        $query = new WP_Query($args);

?>

        <div class="filter-container">

            <div class="filters-desktop lg:block mb-e25">
                <div class="flex-container">
                    <div class="">
                        <ul class="filter-list">

                            <li class="mr-e50 vertical sector"><button class="cursor-pointer flex items-center mr-e20"><span class="text-e15 uppercase font-semibold mr-e18">sector</span>
                                    <div class="chevron"></div>
                                </button>
                            </li>
                            <li class="mr-e50 region"><button class="cursor-pointer flex items-center mr-e20"><span class="text-e15 uppercase font-semibold mr-e18">region</span>
                                    <div class="chevron"></div>
                                </button>
                            </li>
                            <li class="mr-e50 status"><button class="cursor-pointer flex items-center mr-e20"><span class="text-e15 uppercase font-semibold mr-e18">status</span>
                                    <div class="chevron"></div>
                                </button>
                            </li> <!---->
                        </ul>
                    </div>
                    <div class="w-full flex justify-end">
                        <form id="company-search" method="post" __bizdiag="3373707" __biza="WJ__">
                            <input type="text" name="name" placeholder="Search">
                            <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 17.161 17.161" height="16" width="16" class="text-orange svg-icon svg-icon__search">
                                <defs>
                                    <symbol id="search" stroke="none" fill="none" data-viewbox="0 0 17.161 17.161">
                                        <circle cx="6.411" cy="6.411" r="6.411" transform="translate(0.75 0.75)" fill="none" stroke="currentColor" stroke-width="1.5"></circle>
                                        <line x2="4.936" y2="4.936" transform="translate(11.694 11.694)" fill="none" stroke="currentColor" stroke-width="1.5"></line>
                                    </symbol>
                                </defs>
                                <g>
                                    <circle cx="6.411" cy="6.411" r="6.411" transform="translate(0.75 0.75)" fill="none" stroke="currentColor" stroke-width="1.5"></circle>
                                    <line x2="4.936" y2="4.936" transform="translate(11.694 11.694)" fill="none" stroke="currentColor" stroke-width="1.5"></line>
                                </g>
                            </svg>
                            </svg>


                        </form>
                    </div>
                </div>
            </div>
            <div class="active-filters-desktop">
                <div class="hidden lg:block"><!----></div>
                <div class="hidden lg:block"><!----></div>
                <div class="hidden lg:block"><!----></div>
            </div>

        </div>


<?php
        $total_results = $query->found_posts;


        echo '<div class="result-count"></div>';
        echo '<div id="result-container">';
        if ($query->have_posts()) {
            $counter = 0; // Counter for posts
            $shortcode_counter = 0;
            echo '<div class="row-flex">'; // Start a new row div
            while ($query->have_posts()) {
                $query->the_post();

                // Get custom fields
                $post_url = get_permalink();
                $sectors = get_post_meta(get_the_ID(), 'sector', true);
                $sector = !empty($sectors) ? $sectors[0] : '';
                $region = get_post_meta(get_the_ID(), 'region', true);
                $status = get_post_meta(get_the_ID(), 'status', true);
                $investment_date = get_post_meta(get_the_ID(), 'initial_investment', true);
                $investment_year = !empty($investment_date) ? date('F, Y', strtotime($investment_date)) : '';

                $logo_image = get_field('logo');

                // Display the post content
                echo '<div class="company-card portfolio-list-item">';
                echo '<a href="' . esc_url($post_url) . '" class="company-card-link">';

                if ($logo_image) {
                    echo '<img src="' . $logo_image['url'] . '" alt="' . $logo_image['alt'] . '">';
                    // echo " loded from first";
                }

                echo '<div class="portfolio-company-card">';
                echo '<p class="portfolio-card-name">' . get_the_title() . '</p>';
                if (!empty($sector)) {
                    echo '<p><span class="portfolio-card-labal">Sector: </span><span class="portfolio-card-sector">' . esc_html($sector) . '</span></p>';
                }
                if (!empty($region)) {
                    echo '<p><span class="portfolio-card-labal">Region: </span><span class="portfolio-card-region">' . esc_html($region) . '</span></p>';
                }
                if (!empty($status)) {
                    echo '<p><span class="portfolio-card-labal">Status: </span><span class="portfolio-card-status">' . esc_html($status) . '</span></p>';
                }
                if (!empty($investment_year)) {
                    echo '<p><span class="portfolio-card-labal">Investment Year:</span><span class="portfolio-card-investment-year">' . esc_html($investment_year) . '</span></p>';
                }
                echo '</div>';

                echo '</a>'; // Close the anchor tag
                echo '</div>'; // Close company-card div

                $counter++; // Increment the counter

                if ($counter % $settings['alternate_template_interval'] == 0) {

                    $shortcode_counter++;

                    if ($shortcode_counter == 1) {

                        // echo do_shortcode('[elementor-template id="2669"]');

                        echo '<div class="template-wrapper" style="display: ' . esc_attr($settings['display_type']) . ';">';

                        // Output the selected Elementor template
                        if (!empty($settings['elementor_template'])) {
                            echo do_shortcode('[elementor-template id="' . $settings['elementor_template'] . '"]');
                        }

                        echo '</div>'; // Close the wrapper div

                    }
                }
            }
            echo '</div>'; // Close the last row div
            // Reset post data
            wp_reset_postdata();
        } else {
            echo '<p>No portfolio posts found.</p>';
        }
        echo '</div>'; // Close result-container div
        echo '<button class="load-more-btn" style=""> Load More </button>';
    }
}
