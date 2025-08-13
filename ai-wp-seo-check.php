<?php
/**
 * Plugin Name:       AI WP SEO Check
 * Description:       Adjust post and page content SEO using OpenAI suggestions.
 * Version:           0.1.0
 * Requires at least: 6.3
 * Requires PHP:      8.1
 * Author:            OpenAI
 * Text Domain:       ai-wp-seo-check
 */

if ( ! defined( 'ABSPATH' ) ) {
exit; // Exit if accessed directly.
}

define( 'AI_WP_SEO_CHECK_VERSION', '0.1.0' );
define( 'AI_WP_SEO_CHECK_PATH', plugin_dir_path( __FILE__ ) );
define( 'AI_WP_SEO_CHECK_URL', plugin_dir_url( __FILE__ ) );

require_once AI_WP_SEO_CHECK_PATH . 'vendor/autoload.php';

\AiWpSeoCheck\Admin\Settings::init();
\AiWpSeoCheck\Admin\Notices::init();
\AiWpSeoCheck\Admin\MetaBox::init();
