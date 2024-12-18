<?php


namespace PORTFOLIO_PLUGIN\Inc;

use PORTFOLIO_PLUGIN\Inc\Traits\Singleton;

class Filtered_posts{

    use singleton;

    protected function __construct()
    {
        $this->setup_hooks();
    }

    protected function setup_hooks(){

        add_action('wp_ajax_fetch_filtered_posts', [$this, 'fetch_filtered_posts']);
        add_action('wp_ajax_nopriv_fetch_filtered_posts', [$this, 'fetch_filtered_posts']);


    }

    function fetch_filtered_posts() {


        global $wpdb; // Make sure to include the global $wpdb variable

        $vertical_filters = isset($_POST['vertical']) ? explode(',', sanitize_text_field($_POST['vertical'])) : array();
        $region_filters = isset($_POST['region']) ? explode(',', sanitize_text_field($_POST['region'])) : array();
        $status_filters = isset($_POST['status']) ? explode(',', sanitize_text_field($_POST['status'])) : array();
        $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1; // Get the current page number

        $meta_query = array('relation' => 'AND');

        if (!empty($vertical_filters)) {
            $vertical_sub_query = array('relation' => 'OR');
            foreach ($vertical_filters as $filter) {
                $vertical_sub_query[] = array(
                    'key' => 'sector',
                    'value' => $filter,
                    'compare' => 'LIKE'
                );
            }
            $meta_query[] = $vertical_sub_query;
        }

        if (!empty($region_filters)) {
            $region_sub_query = array('relation' => 'OR');
            foreach ($region_filters as $filter) {
                $region_sub_query[] = array(
                    'key' => 'region',
                    'value' => $filter,
                    'compare' => 'LIKE'
                );
            }
            $meta_query[] = $region_sub_query;
        }

        if (!empty($status_filters)) {
            $status_sub_query = array('relation' => 'OR');
            foreach ($status_filters as $filter) {
                $status_sub_query[] = array(
                    'key' => 'status',
                    'value' => $filter,
                    'compare' => 'LIKE'
                );
            }
            $meta_query[] = $status_sub_query;
        }

        // Ensure that the meta_query is not empty
        if (count($meta_query) == 1) {
            $meta_query = array();
        }


        $posts_per_page = 8; // Number of posts to load per page

        $args = array(
            'post_type' => 'portfolio',
            'posts_per_page' => $posts_per_page,
            'paged' => $page,
            'meta_query' => $meta_query,
            's' => $search // Add the search term to the query arguments
        );

        $query = new \WP_Query($args);

        $total_results = $query->found_posts;

        $posts_html = '';
        // $posts_html .= '<div class="result-count">' . $total_results . ' results found</div>';
        // $posts_html .= '<div class="row-flex">';


        if ($query->have_posts()) {
            // $counter = 0; // Counter for posts
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

                // Start a new row div every 3 posts

                // Display the post content
                $posts_html .= '<div class="company-card portfolio-list-item">';
                $posts_html .= '<a href="' . esc_url($post_url) . '" class="company-card-link">';

                if ($logo_image) {
                    $posts_html .= '<img src="' . $logo_image['url'] . '" alt="' . $logo_image['alt'] . '">';
                }

                $posts_html .= '<div class="portfolio-company-card">';
                $posts_html .= '<p class="portfolio-card-name">' . get_the_title() . '</p>';

                if (!empty($sector)) {
                    $posts_html .= '<p><span class="portfolio-card-labal">Sector:</span> <span class="portfolio-card-sector">' . esc_html($sector) . '</span></p>';
                }

                if (!empty($region)) {
                    $posts_html .= '<p><span class="portfolio-card-labal">Region:</span> <span class="portfolio-card-region">' . esc_html($region) . '</span></p>';
                }

                if (!empty($status)) {
                    $posts_html .= '<p><span class="portfolio-card-labal">Status:</span> <span class="portfolio-card-status">' . esc_html($status) . '</span></p>';
                }

                if (!empty($investment_year)) {
                    $posts_html .= '<p><span class="portfolio-card-labal">Investment Year:</span> <span class="portfolio-card-investment-year">' . esc_html($investment_year) . '</span></p>';
                }
                $posts_html .= '</div>';
                $posts_html .= '</a>';  // Close the anchor tag


                $posts_html .= '</div>'; // Close company-card div

            }
        } else {
            $posts_html .= '<div class="post">No more found.</div>';
        }

        $posts_html .= '</div>';

        wp_reset_postdata();

        $has_more = ($page * $posts_per_page) < $total_results; // Check if there are more posts to load

        echo json_encode(array('posts_html' => $posts_html, 'has_more' => $has_more, 'total_results' => $total_results));
        wp_die();
    }




}

?>