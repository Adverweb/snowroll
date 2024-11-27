<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Lebagol_Elementor_Product_Review_Control')) :

    /**
     * The Lebagol Elementor Integration class
     */
    class Lebagol_Elementor_Product_Review_Control {

        public function __construct() {

            add_action('elementor/ajax/register_actions', [$this, 'register_ajax_actions']);
            add_action('elementor/controls/controls_registered', [$this, 'on_controls_registered']);
        }

        public function ajax_posts_filter_autocomplete(array $data) {
            if ( empty( $data['q'] ) ) {
                throw new \Exception( 'Bad Request' );
            }

            $results = [];

            $query_params = [
                'status' => 'approve',
                'type' => 'review',
                'post_type' => 'product',
                'search' => $data['q'],
                'number' => '',
            ];

            $reviews_query = new WP_Comment_Query;
            $reviews = $reviews_query->query($query_params);

            if ($reviews) {
                foreach ($reviews as $review) {
                    if (stripos($review->comment_content, $data['q']) !== false) {
                        $results[] = [
                            'id' => $review->comment_ID,
                            'text' => esc_html( $review->comment_content ),
                        ];
                    }
                }
            }

            return [
                'results' => $results,
            ];
        }

        public function ajax_query_control_value_product($request) {
            $ids = (array) $request['id'];
            $results = [];
            $reviews_query = new WP_Comment_Query;
            $reviews = $reviews_query->query(
                [
                    'type' => 'review',
                    'post_type' => 'product',
                    'comment__in' => $ids,
                ]
            );

            if ($reviews) {
                foreach ($reviews as $review) {
                    $results[ $review->comment_ID ] = esc_html( $review->comment_content );
                }
            }
            return $results;
        }

        public function register_ajax_actions($ajax_manager) {
            $ajax_manager->register_ajax_action('panel_posts_control_filter_product_review', [$this, 'ajax_posts_filter_autocomplete']);
            $ajax_manager->register_ajax_action('query_control_value_product_review', [$this, 'ajax_query_control_value_product']);
        }

        public function on_controls_registered() {
            $this->register_control();
        }

        private function register_control() {
            require get_theme_file_path('inc/elementor/product_review/product-control.php');
            $controls_manager = \Elementor\Plugin::instance()->controls_manager;
            $controls_manager->register( new Products_Review_Control());
        }

    }

endif;

return new Lebagol_Elementor_Product_Review_Control();
