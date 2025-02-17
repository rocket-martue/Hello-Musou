<?php
/**
 * Plugin Name: Hello Musou
 * Plugin URI: https://github.com/rocket-martue/Hello-Musou
 * Description: This is not just a plugin, When activated you will randomly see a text from <cite>Musou text</cite> in the upper right of your admin screen on every page.By default, this plugin reads `musou-sample.txt` in the plugin directory.If you want to customize the display text, upload `wp-content / musou.txt`.
 * Author: Rocket Martue
 * Version: 0.2.0
 * Text Domain: hello-musou
 * Domain Path: /languages/
 * Update URI: https://github.com/rocket-martue/Hello-Musou/releases
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html

 * @package Hello-Musou
 */

/**
 * Load the plugin's text domain.
 */
function hello_musou_init() {
	load_plugin_textdomain( 'hello-musou', false, dirname( plugin_basename( __FILE__ ) ) . '/language' );
}
add_action( 'plugins_loaded', 'hello_musou_init' );

/**
 * Retrieves a specific text string.
 *
 * This function returns a predefined text string. It can be used to
 * demonstrate basic function documentation.
 *
 * @return string The text string "Hello, Musou!".
 */
function hello_musou_get_text() {
	$plugin_path = plugin_dir_path( __FILE__ );

	/** These are the texts to Hello musou */
	if ( file_exists( WP_CONTENT_DIR . '/uploads/musou.txt' ) ) {
		$filename = WP_CONTENT_DIR . '/uploads/musou.txt';
	} else {
		$filename = $plugin_path . 'musou-sample.txt';
	}
	$musou_text = file_get_contents( $filename );

	// Here we split it into lines.
	$musou_text = explode( "\n", $musou_text );

	// And then randomly choose a line.
	return wptexturize( $musou_text[ wp_rand( 0, count( $musou_text ) - 1 ) ] );
}

/**
 * This just echoes the chosen line, we'll position it later.
 *
 * @return void
 */
function hello_musou() {
	$chosen = hello_musou_get_text();
	$lang   = '';
	if ( 'en_' !== substr( get_user_locale(), 0, 3 ) ) {
		$lang = ' lang="en"';
	}

	printf(
		'<p id="musou"><span class="screen-reader-text">%s </span><span dir="ltr"%s>%s</span></p>',
		esc_html__( 'Quote from WP ZoomUP, by Musou:', 'hello-musou' ),
		esc_attr( $lang ),
		esc_html( $chosen )
	);
}

// Now we set that function up to execute when the admin_notices action is called.
add_action( 'admin_notices', 'hello_musou' );

// We need some CSS to position the paragraph.
/**
 * Enqueues the Musou CSS stylesheet.
 *
 * This function is responsible for adding the Musou CSS file to the WordPress
 * theme or plugin. It ensures that the stylesheet is properly enqueued and
 * available for use on the front-end of the site.
 *
 * @return void
 */
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
