<?php

namespace PORTFOLIO_PLUGIN\Inc\Traits;

trait Render_Trait {

    public function render_widget(){

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

        $query = new \WP_Query($args);

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
