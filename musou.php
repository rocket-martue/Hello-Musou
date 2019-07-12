<?php
/**
 * @package Hello_Musou
 * @version 0.0.1
 */
/*
Plugin Name: Hello Musou
Description: This is not just a plugin, When activated you will randomly see a text from <cite>Musou text</cite> in the upper right of your admin screen on every page.
Author: Rocket Martue
Version: 0.0.1
*/

function hello_musou_get_text() {
	$plugin_path = plugin_dir_path( __FILE__ );

	/** These are the texts to Hello musou **/
	$filename = $plugin_path . 'musou.txt';
	$musou_text = file_get_contents( $filename );

	// Here we split it into lines.
	$musou_text = explode( "\n", $musou_text );

	// And then randomly choose a line.
	return wptexturize( $musou_text[ mt_rand( 0, count( $musou_text ) - 1 ) ] );
}

// This just echoes the chosen line, we'll position it later.
function hello_musou() {
	$chosen = hello_musou_get_text();
	$lang   = '';
	if ( 'en_' !== substr( get_user_locale(), 0, 3 ) ) {
		$lang = ' lang="en"';
	}

	printf(
		'<p id="musou"><span class="screen-reader-text">%s </span><span dir="ltr"%s>%s</span></p>',
		__( 'Quote from WP ZoomUP, by Musou:', 'hello-musou' ),
		$lang,
		$chosen
	);
}

// Now we set that function up to execute when the admin_notices action is called.
add_action( 'admin_notices', 'hello_musou' );

// We need some CSS to position the paragraph.
function musou_css() {
	echo "
	<style type='text/css'>
	#musou {
		float: right;
		padding: 5px 10px;
		margin: 0;
		font-size: 12px;
		line-height: 1.6666;
	}
	.rtl #musou {
		float: left;
	}
	.block-editor-page #musou {
		display: none;
	}
	@media screen and (max-width: 782px) {
		#musou,
		.rtl #musou {
			float: none;
			padding-left: 0;
			padding-right: 0;
		}
	}
	</style>
	";
}

add_action( 'admin_head', 'musou_css' );