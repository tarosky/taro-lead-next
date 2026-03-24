/**
 * E2E tests for the Lead to Next block (tarosky/lead).
 *
 * Prerequisites:
 *   npm start       — start wp-env
 *   npm run package — build assets (wp-dependencies.json must exist)
 *
 * Run:
 *   npm run test:e2e
 *   npm run test:e2e:debug  — with visible browser
 */

import { test, expect } from '@wordpress/e2e-test-utils-playwright';

test.describe( 'Lead to Next Block', () => {
	test.beforeAll( async ( { requestUtils } ) => {
		await requestUtils.activateTheme( 'twentytwentyfive' );
	} );

	test( 'can be inserted into the editor', async ( { admin, editor } ) => {
		await admin.createNewPost();
		await editor.insertBlock( { name: 'tarosky/lead' } );

		const blocks = await editor.getBlocks();
		expect( blocks ).toHaveLength( 1 );
		expect( blocks[ 0 ].name ).toBe( 'tarosky/lead' );
	} );

	test( 'can modify text and attributes via sidebar', async ( {
		admin,
		editor,
		page,
	} ) => {
		await admin.createNewPost();
		await editor.insertBlock( { name: 'tarosky/lead' } );

		// Click the RichText body field (no iframe in WP 6.9+).
		await page
			.locator(
				'[data-type="tarosky/lead"] .taro-lead-next-body'
			)
			.click();
		await page.keyboard.type( 'Continue to the next page' );

		// Open block settings sidebar.
		await editor.openDocumentSettingsSidebar();

		// Change the title (use exact role match to avoid Query Monitor noise).
		const titleInput = page.getByRole( 'textbox', {
			name: 'Title',
			exact: true,
		} );
		await titleInput.fill( '' );
		await titleInput.fill( 'Read More' );

		// Change alignment to center.
		const alignSelect = page.getByLabel( 'Text Alignment' );
		await alignSelect.selectOption( 'center' );

		// Verify the serialised block markup contains the expected values.
		const content = await editor.getEditedPostContent();
		expect( content ).toContain( 'Continue to the next page' );
		expect( content ).toContain( 'Read More' );
		expect( content ).toContain( 'has-text-align-center' );
	} );

	test( 'renders correctly on the frontend', async ( {
		admin,
		editor,
		page,
	} ) => {
		await admin.createNewPost( { title: 'Lead Block E2E Test' } );

		// Insert lead block with preset attributes.
		await editor.insertBlock( {
			name: 'tarosky/lead',
			attributes: {
				text: 'Discover what happens next',
				title: 'Next Page',
				align: 'center',
			},
		} );

		// Page break is required for the lead block to function.
		await editor.insertBlock( { name: 'core/nextpage' } );

		// Content on the second page.
		await editor.insertBlock( {
			name: 'core/paragraph',
			attributes: { content: 'Second page content.' },
		} );

		// Publish the post.
		await editor.publishPost();

		// Navigate to the published post on the frontend.
		const postId = await page.evaluate( () =>
			wp.data.select( 'core/editor' ).getCurrentPostId()
		);
		await page.goto( `/?p=${ postId }` );

		// Verify the lead block wrapper is visible.
		const leadBlock = page.locator( '.taro-lead-next' );
		await expect( leadBlock ).toBeVisible();

		// Verify the link element.
		const link = leadBlock.locator( 'a[rel="next"]' );
		await expect( link ).toBeVisible();
		await expect( link ).toHaveClass( /has-text-align-center/ );

		// Verify title.
		await expect(
			leadBlock.locator( '.taro-lead-next-title' )
		).toHaveText( 'Next Page' );

		// Verify body text.
		await expect(
			leadBlock.locator( '.taro-lead-next-body' )
		).toHaveText( 'Discover what happens next' );

		// Verify the %link% placeholder was replaced with an actual URL.
		const href = await link.getAttribute( 'href' );
		expect( href ).not.toContain( '%link%' );
		expect( href ).toBeTruthy();
	} );
} );
