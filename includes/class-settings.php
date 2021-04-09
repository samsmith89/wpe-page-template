<?php
namespace WPT\Includes;

use WPT;
use WPT\Includes\Settings_Table as Settings_Table;

/**
 * Creates the additional admin setting page that displays the summary of page templates
 *
 * @since 1.0.0
 *
 */

class Settings {

	protected static $_instance;

	public static function get_instance() {
		if ( ! self::$_instance instanceof self ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_settings_page' ] );
	}

	/**
	 * Adds the Menu page for the WPT summary
	 *
	 * @since 1.0.0
	 *
	 * @see add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position  )
	 * @link https://developer.wordpress.org/reference/functions/add_menu_page/
	 */

	public function add_settings_page() {
		add_menu_page( 'WPE Templates', 'WPE Templates', 'manage_options', 'wpe-templates', [ $this, 'render_plugin_settings_page'] );
	}

	/**
	 * Renders the markup for the Settings page
	 *
	 * @since 1.0.0
	 *
	 * @see add_settings_page()
	 * @see Settings_Table class
	 * @see prepare_items()
	 * @see display()
	 */

	public function render_plugin_settings_page() {
		$post_obj = new Settings_Table();
		?>
		<div class="wrap">
			<h2><?php _e( 'WPE Templates', WPT::get_id() ) ?></h2>
			<?php $post_obj->prepare_items(); ?>
			<div id="wpt-template-wrap">
				<form id="templates-filter" method="get">
					<?php $post_obj->display(); ?>
				</form>
			</div>
		</div>
		<?php
	}
}
