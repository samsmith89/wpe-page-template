<?php
namespace WPT\Includes;

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
		add_action( 'admin_menu', [ $this, 'wpt_add_settings_page' ] );
	}

	public function wpt_add_settings_page() {
		add_menu_page( 'WPE Templates', 'WPE Templates', 'manage_options', 'wpe-templates', [ $this, 'wpt_render_plugin_settings_page'] );
	}

	public function wpt_render_plugin_settings_page() {
		$post_obj = new Settings_Table();
		?>
		<div class="wrap">
			<h2><?php __( 'WPT Templates', 'wpt' ) ?></h2>
			<?php
			if( isset($_POST['s']) ){
				$post_obj->prepare_items($_POST['s']);
			} else {
				$post_obj->prepare_items();
			}
			?>
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<form id="posts-filter" method="get">
								<?php
								$post_obj->search_box("Search Templates", "search_templates_id");
								$post_obj->display();
								?>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
