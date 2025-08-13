<?php
namespace AiWpSeoCheck\Admin;

use AiWpSeoCheck\Infrastructure\OpenAIClient;

class MetaBox {
    public static function init(): void {
        add_action( 'add_meta_boxes', [ __CLASS__, 'register_box' ] );
        add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_scripts' ] );
        add_action( 'wp_ajax_ai_wp_seo_check_adjust', [ __CLASS__, 'handle_ajax' ] );
    }

    public static function register_box(): void {
        foreach ( [ 'post', 'page' ] as $post_type ) {
            add_meta_box(
                'ai-wp-seo-check',
                __( 'AI WP SEO Check', 'ai-wp-seo-check' ),
                [ __CLASS__, 'render_box' ],
                $post_type,
                'side'
            );
        }
    }

    public static function render_box(): void {
        wp_nonce_field( 'ai_wp_seo_check', 'ai_wp_seo_check_nonce' );
        echo '<button type="button" class="button" id="ai-wp-seo-check-adjust">' . esc_html__( 'Adjust SEO', 'ai-wp-seo-check' ) . '</button>';
        echo '<p class="ai-wp-seo-check-feedback" style="margin-top:10px;"></p>';
    }

    public static function enqueue_scripts( string $hook ): void {
        if ( 'post.php' !== $hook && 'post-new.php' !== $hook ) {
            return;
        }
        wp_enqueue_script(
            'ai-wp-seo-check-admin',
            AI_WP_SEO_CHECK_URL . 'assets/js/admin.js',
            [ 'jquery', 'wp-data' ],
            AI_WP_SEO_CHECK_VERSION,
            true
        );
        wp_localize_script(
            'ai-wp-seo-check-admin',
            'AiWpSeoCheck',
            [
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'ai_wp_seo_check' ),
                'missingKey' => empty( get_option( Settings::OPTION_KEY ) ),
            ]
        );
    }

    public static function handle_ajax(): void {
        check_ajax_referer( 'ai_wp_seo_check', 'nonce' );

        $api_key = get_option( Settings::OPTION_KEY );
        if ( empty( $api_key ) || ! current_user_can( 'edit_posts' ) ) {
            wp_send_json_error( __( 'Missing API key.', 'ai-wp-seo-check' ) );
        }

        $content = wp_unslash( $_POST['content'] ?? '' );
        $client  = new OpenAIClient( $api_key );
        $result  = $client->adjust_content( $content );

        if ( $result === $content ) {
            wp_send_json_error( __( 'No changes made.', 'ai-wp-seo-check' ) );
        }

        wp_send_json_success( [ 'content' => $result ] );
    }
}
