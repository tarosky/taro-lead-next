<?php
/**
 * Test block functions.
 *
 * @package tsln
 */

class TestBlock extends WP_UnitTestCase {

	/**
	 * Test default title returns a string.
	 */
	public function test_default_title_returns_string() {
		$title = tsln_default_title();
		$this->assertIsString( $title );
		$this->assertNotEmpty( $title );
	}

	/**
	 * Test default title filter.
	 */
	public function test_default_title_filter() {
		$callback = function () {
			return 'Custom Title';
		};
		add_filter( 'tsln_next_page_default_title', $callback );
		$this->assertSame( 'Custom Title', tsln_default_title() );
		remove_filter( 'tsln_next_page_default_title', $callback );
	}

	/**
	 * Test version returns string.
	 */
	public function test_version_returns_string() {
		$version = tsln_version();
		$this->assertIsString( $version );
	}

	/**
	 * Test URL returns string.
	 */
	public function test_url_returns_string() {
		$url = tsln_url();
		$this->assertIsString( $url );
		$this->assertNotEmpty( $url );
	}

	/**
	 * Test block type is registered after init.
	 */
	public function test_block_type_registered() {
		// Trigger init hooks if not already fired.
		do_action( 'init' );
		$registry = WP_Block_Type_Registry::get_instance();
		$this->assertTrue( $registry->is_registered( 'tarosky/lead' ) );
	}

	/**
	 * Test render_block filter replaces %link% placeholder.
	 */
	public function test_render_block_replaces_link_placeholder() {
		$post_id = self::factory()->post->create( [
			'post_content' => '<!-- wp:tarosky/lead -->Page 1<!-- /wp:tarosky/lead -->',
			'post_status'  => 'publish',
		] );
		// Set up global post.
		global $post;
		$post = get_post( $post_id );
		setup_postdata( $post );
		// Set permalink structure.
		$this->set_permalink_structure( '/%postname%/' );

		$block_content = '<a href="%link%">Next Page</a>';
		$parsed_block  = [
			'blockName' => 'tarosky/lead',
		];
		$result = apply_filters( 'render_block', $block_content, $parsed_block );
		$this->assertStringNotContainsString( '%link%', $result );
		$this->assertStringContainsString( '<a href=', $result );

		wp_reset_postdata();
	}

	/**
	 * Test render_block does not modify other blocks.
	 */
	public function test_render_block_ignores_other_blocks() {
		$block_content = '<p>%link%</p>';
		$parsed_block  = [
			'blockName' => 'core/paragraph',
		];
		$result = apply_filters( 'render_block', $block_content, $parsed_block );
		$this->assertStringContainsString( '%link%', $result );
	}
}
