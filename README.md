# Taro Lead Next

Tags: series, posts, pagination, next-page, block  
Contributors: tarosky, Takahashi_Fumiki, tswallie  
Tested up to: 7.0  
Stable Tag: nightly  
License: GPLv3 or later  
License URI: http://www.gnu.org/licenses/gpl-3.0.txt

Add simple block to invoke click to next page.

## Description

This plugin add a simple block to call user attention to click next page of paginated posts.

### Customization

### Block Settings

No coding required for basic customization. Select the block in the editor and open the sidebar (Inspector Controls) to change:

- **Title**: The heading text shown above the lead text. Leave empty to omit it.
- **Text Alignment**: Left / Center / Right / Default.

The lead text itself is edited directly and inline, just like a paragraph block.

### Style

HTML structure is like this.

```
<div class="taro-lead-next">
	<a href="%link%" class="taro-lead-next-link" rel="next">
		<!-- Title will be omitted if empty. -->
		<p class="taro-lead-next-title">
			Next Page
		</p>
		<p class="taro-lead-next-body">
			Click next page and find the result!
		</p>
	</a>
</div>
```

In block editor, `a.taro-lead-next-line` will be `<div>` tag.

Default stylesheet is registered as `tsln-lead-block` in `init` hook at the priority 20.
For full customization, register your style as a same name. The style registered faster priors to plugin's style.

```
add_action( 'init', function() {
	wp_register_style( 'tsln-lead-block', 'your-theme/original.css', [], '1.0.0' );
}, 10 );
```

### Header

Default header title is "Next Page". You can change it per block from the sidebar (see "Block Settings" above). To change the site-wide default instead, use this filter hook:

```
add_filter( 'tsln_next_page_default_title', function( $title ) {
	return "What's Next?";
} );
```

## Installation

### From Plugin Repository

Click install and activate it.

### From GitHub

See [releases](https://github.com/tarosky/taro-lead-next/releases).

## FAQ

### Where can I get supported?

Please create new ticket on support forum.

### How can I contribute?

Create a new [issue](https://github.com/tarosky/taro-lead-next/issues) or send [pull requests](https://github.com/tarosky/taro-lead-next/pulls).

## Changelog

### 1.2.1

* Document existing block sidebar settings (Title / Text Alignment) instead of implying PHP is required.
* Update plugin tags for better search relevance (drop unrelated "news", add "pagination", "next-page", "block").
* Fix broken HTML rendering in the Description page's code samples.

### 1.2.0

* Bump minimum requirements: WordPress 6.6, PHP 7.4
* Renew build system.

### 1.1.0

* Drop support under WordPress 6.1
* Fix warning on block editor.

### 1.0.4

* Add script translation.

### 1.0.0

* First release.
