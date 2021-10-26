<?php
/**
Plugin Name: Taro Lead Next
Plugin URI: https://wordpress.org/plugins/taro-lead-next/
Description: Add 1 block to next page lead.
Author: Tarosky INC.
Version: nightly
Author URI: https://tarosky.co.jp/
License: GPL3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Text Domain: tsln
Domain Path: /languages
 */

defined( 'ABSPATH' ) or die();

/**
 * Init plugins.
 */
function tsln_init() {
	// Register translations.
	load_plugin_textdomain( 'tsln', false, basename( __DIR__ ) . '/languages' );
	// Register assets.
	add_action( 'init', 'tsln_register_assets', 20 );
	// Register blocks.
	add_action( 'init', 'tsln_register_blocks', 21 );
}

/**
 * Register assets.
 */
function tsln_register_assets() {
	// Register assets.
	$root    = tsln_url();
	$version = tsln_version();
	// JS
	wp_register_script( 'tsln-lead-block', $root . '/dist/js/lead-block.js', [
		'wp-blocks',
		'wp-i18n',
		'wp-block-editor',
		'wp-components',
		'wp-data'
	], $version, true );
	wp_localize_script( 'tsln-lead-block', 'TaroLeadNextBlockVars', [
		'title' => tsln_default_title(),
	] );
	// Style
	wp_register_style( 'tsln-lead-block', $root . '/dist/css/lead-block.css', [ 'wp-components' ], $version );
	wp_register_style( 'tsln-lead-block-editor', $root . '/dist/css/lead-block-editor.css', [ 'wp-components', 'tsln-lead-block' ], $version );
}

/**
 * Register blocks.
 *
 * @see _wp_link_page()
 */
function tsln_register_blocks() {
	// Register blocks.
	register_block_type( 'tarosky/lead-block', [
		'editor_script' => 'tsln-lead-block',
		'style'         => 'tsln-lead-block',
		'editor_style'  => 'tsln-lead-block-editor',
	] );

	// Replace link.
	add_filter( 'render_block', function( $block, $parsed_block ) {
		if ( 'tarosky/lead' === $parsed_block['blockName'] ) {
			// Replace href to next page.
			$next_page  = max( 1, (int) get_query_var( 'page' ) ) + 1;
			if ( ! get_option( 'permalink_structure' ) || in_array( get_post_status(), array( 'draft', 'pending' ), true ) ) {
				$url = add_query_arg( [
					'page' => $next_page,
				], get_permalink() );
			} else {
				$url = trailingslashit( get_permalink() ) . user_trailingslashit( $next_page, 'single_paged' );
			}
			if ( is_preview() ) {
				$query_args = [];
				if ( ( 'draft' !== get_post_status() ) && isset( $_GET['preview_id'], $_GET['preview_nonce'] ) ) {
					$query_args['preview_id']    = wp_unslash( $_GET['preview_id'] );
					$query_args['preview_nonce'] = wp_unslash( $_GET['preview_nonce'] );
				}
				$url = get_preview_post_link( get_post(), $query_args, $url );
			}
			$block = str_replace( '%link%', $url, $block );
		}
		return $block;
	}, 10, 2 );
}

/**
 * Get default next page.
 *
 * @return string
 */
function tsln_default_title() {
	return apply_filters( 'tsln_next_page_default_title', __( 'Next Page', 'tsln' ) );
}

/**
 * Get URL.
 *
 * @return string
 */
function tsln_url() {
	return untrailingslashit( plugin_dir_url( __FILE__ ) );
}

/**
 * Get version.
 *
 * @return string
 */
function tsln_version() {
	static $version = null;
	if ( is_null( $version ) ) {
		$data    = get_file_data( __FILE__, [
			'version' => 'Version',
		] );
		$version = $data['version'];
	}
	return $version;
}

// Register hooks.
add_action( 'plugins_loaded', 'tsln_init' );
