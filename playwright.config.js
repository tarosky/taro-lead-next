const baseConfig = require( '@wordpress/scripts/config/playwright.config.js' );
const { defineConfig } = require( '@playwright/test' );

module.exports = defineConfig( {
	...baseConfig,
	testDir: './specs',
} );
