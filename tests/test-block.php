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
		// Block is already registered during plugin bootstrap, no need to re-fire init.
		$registry = WP_Block_Type_Registry::get_instance();
		$this->assertTrue( $registry->is_registered( 'tarosky/lead' ) );
	}

	/**
	 * Test render_block filter is registered.
	 */
	public function test_render_block_filter_registered() {
		$this->assertGreaterThan( 0, has_filter( 'render_block' ), 'render_block filter should be registered.' );
	}
}
