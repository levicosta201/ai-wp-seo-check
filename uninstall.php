<?php
/**
 * Uninstall handler for AI WP SEO Check.
 *
 * @package AiWpSeoCheck
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
exit;
}

$option = 'ai_wp_seo_check_api_key';

delete_option( $option );

