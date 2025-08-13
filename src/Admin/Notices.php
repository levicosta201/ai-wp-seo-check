<?php
namespace AiWpSeoCheck\Admin;

class Notices {
    public static function init(): void {
        add_action( 'admin_notices', [ __CLASS__, 'maybe_show_api_notice' ] );
    }

    public static function maybe_show_api_notice(): void {
        if ( get_option( Settings::OPTION_KEY ) ) {
            return;
        }
        $message = 'AI WP SEO Check requires an OpenAI API key. Please set one in Settings.';
        echo '<div class="notice notice-warning"><p>' . esc_html__( $message, 'ai-wp-seo-check' ) . '</p></div>';
    }
}
