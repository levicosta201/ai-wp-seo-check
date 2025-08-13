<?php
namespace AiWpSeoCheck\Admin;

class Settings {
    const OPTION_KEY = 'ai_wp_seo_check_api_key';

    public static function init(): void {
        add_action( 'admin_menu', [ __CLASS__, 'register_page' ] );
        add_action( 'admin_init', [ __CLASS__, 'register_setting' ] );
    }

    public static function register_page(): void {
        add_options_page(
            __( 'AI WP SEO Check', 'ai-wp-seo-check' ),
            __( 'AI WP SEO Check', 'ai-wp-seo-check' ),
            'manage_options',
            'ai-wp-seo-check',
            [ __CLASS__, 'render_page' ]
        );
    }

    public static function register_setting(): void {
        register_setting( 'ai_wp_seo_check', self::OPTION_KEY, [ 'sanitize_callback' => 'sanitize_text_field' ] );

        add_settings_section(
            'ai_wp_seo_check_section',
            __( 'API Settings', 'ai-wp-seo-check' ),
            '__return_false',
            'ai_wp_seo_check'
        );

        add_settings_field(
            self::OPTION_KEY,
            __( 'OpenAI API Key', 'ai-wp-seo-check' ),
            [ __CLASS__, 'render_field' ],
            'ai_wp_seo_check',
            'ai_wp_seo_check_section'
        );
    }

    public static function render_page(): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'AI WP SEO Check', 'ai-wp-seo-check' ); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields( 'ai_wp_seo_check' );
                do_settings_sections( 'ai_wp_seo_check' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public static function render_field(): void {
        $value = get_option( self::OPTION_KEY, '' );
        ?>
        <input type="text" class="regular-text" name="<?php echo esc_attr( self::OPTION_KEY ); ?>" value="<?php echo esc_attr( $value ); ?>" />
        <?php
    }
}
