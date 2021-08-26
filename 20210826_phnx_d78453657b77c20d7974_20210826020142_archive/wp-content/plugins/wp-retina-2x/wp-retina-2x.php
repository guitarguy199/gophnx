<?php
/*
Plugin Name: Perfect Images (Retina, Thumbnails, Replace)
Plugin URI: https://meowapps.com
Description: Retina, Replace Images, Regenerate Thumbnails, Image Sizes Management, Image Threshold and more.
Version: 6.1.4
Author: Jordy Meow
Author URI: https://meowapps.com
Text Domain: wp-retina-2x
Domain Path: /languages

Originally developed for two of my websites:
- Jordy Meow (https://offbeatjapan.org)
- Haikyo (https://haikyo.org)
*/

if ( !get_option( 'wr2x_version_6_0_0' ) ) {
	function wr2x_version_6_0_0_admin_notices() {
		echo '<div class="notice notice-error"><h2>WP Retina 2x is now called... <b>Perfect Images!</b></h2>';
    echo '<p>WordPress is evolving, the way we are using images as well. I knew I had to simplify how Retina works by default (while keeping the same options), but also that I had to provide features to manage your image sizes better. Along with this, the features which were buried in the plugin are now much more obvious and easier to use (Replace Images, Regenerate Thumbnails, etc). This plugin will now evolve with WordPress, making sure best practices are easily actionable. Thanks for your support! And if you have some time, do not hesitate to let a little review <a href="https://wordpress.org/support/plugin/wp-retina-2x/reviews/?rate=5#new-post">here</a>. :)</p>';
		echo '<p>
			<form method="post" action="">
				<input type="hidden" name="wr2x_version_6_0_0" value="true">
				<input type="submit" name="submit" id="submit" class="button" value="Got it. Hide this forever!">
			</form>
		</p>
		';
		echo '</div>';
	}
	if ( isset( $_POST['wr2x_version_6_0_0'] ) ) {
		update_option( 'wr2x_version_6_0_0', true, false );
	}
	else
		add_action( 'admin_notices', 'wr2x_version_6_0_0_admin_notices' );
}

// We should enable this later, to avoid the two update messages to show at the same
// time... maybe, let's do this in December?

if ( !get_option( 'wr2x_notice_easyio' ) ) {
	function wr2x_easy_io_admin_notices() {
		echo '<div class="notice notice-error"><h2>Perfect Images brings its own all-in one Image Optimization + CDN offer! 🥳<b></b></h2>';
		echo '<p>This is a big news, as I dreamed of this for many years! I really wanted to bring perfect image optimization coupled with a CDN, while being easy to use and affordable. And now, thanks to a partnership with EWWW (which has really nice back-end infrastructure), it is here! Please check the Settings, in <b>Meow Apps > Perfect Images > Optimization & Speed</b>. Enjoy and let me know how it goes!
		</p>';
		echo '<p>
			<form method="post" action="">
				<input type="hidden" name="wr2x_notice_easyio" value="true">
				<input type="submit" name="submit" id="submit" class="button" value="Nice! But please hide that now!">
			</form>
		</p>
		';
		echo '</div>';
	}
	if ( isset( $_POST['wr2x_notice_easyio'] ) ) {
		update_option( 'wr2x_notice_easyio', true, false );
	}
	else
		add_action( 'admin_notices', 'wr2x_easy_io_admin_notices' );
}

define( 'WR2X_VERSION', '6.1.4' );
define( 'WR2X_PREFIX', 'wr2x' );
define( 'WR2X_DOMAIN', ' wp-retina-2x' );
define( 'WR2X_ENTRY', __FILE__ );
define( 'WR2X_PATH', dirname( __FILE__ ) );
define( 'WR2X_URL', plugin_dir_url( __FILE__ ) );

define ( 'WR2X_VERSION_RETINAJS', '5.6.1' );
define ( 'WR2X_VERSION_PICTUREFILL', '3.0.2' );
define ( 'WR2X_VERSION_LAZYSIZES', '5.1.0' );
define ( 'WR2X_VERSION_RETINA_IMAGES', '1.7.2' );

require_once( 'classes/init.php');

?>
