# Taro Lead Next

Tags: series, posts, news  
Contributors: tarosky, Takahashi_Fumiki  
Tested up to: 5.8  
Requires at least: 5.4  
Requires PHP: 5.6  
Stable Tag: nightly  
License: GPLv3 or later  
License URI: http://www.gnu.org/licenses/gpl-3.0.txt

Add simple block to invoke clikc to next page.

## Description

This plugin add a simple block to call user attention to click next page of paginated posts.

### Customization

### Style

HTML scructure is like this.

```
&lt;div className="taro-lead-next"&gt;
	&lt;a href="%link%" class="taro-lead-next-link" rel="next"&gt;
		&lt;!-- Title will be omitted if empty. --&gt;
		&lt;p className="taro-lead-next-title"&gt;
			Next Page
		&lt;/p&gt;
		&lt;p class="taro-lead-next-body"&gt;
			Click next page and find the result!
		&lt;/p&gt;
	&lt;/a&gt;
&lt;/div&gt;
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

Default header title is "Next Page". We have a plan to make it customizable from Theme Customizer, but you can change it by filter hook for the present.

```
add_filter( 'tsln_next_page_default_title', function( $title ) {
	return "What's Next?";
} );
```

## Installation

### From Plugin Repository

Click install and activate it.

### From Github

See [releases](https://github.com/tarosky/taro-lead-next/releases).

## FAQ

### Where can I get supported?

Please create new ticket on support forum.

### How can I contribute?

Create a new [issue](https://github.com/tarosky/taro-lead-next/issues) or send [pull requests](https://github.com/tarosky/taro-lead-next/pulls).

## Changelog

### 1.0.4

* Add script translation.

### 1.0.0

* First release.
