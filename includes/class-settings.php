<?php
require_once( dirname( __FILE__ ) . "/class-settings-table.php" );

/**
 * Creates the additional admin setting page that displays the summary of page templates
 *
 * @since 1.0.0
 *
 */


class WPTSettings {

	protected static $_instance;

	public static function get_instance() {
		if ( ! self::$_instance instanceof self ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'wpt_add_settings_page' ] );
//		add_filter( 'manage_wpe-templates_page_columns', [ 'WPT_Pages_List', 'get_my_columns' ] );
	}

	public function wpt_add_settings_page() {
		add_menu_page( 'WPE Templates', 'WPE Templates', 'manage_options', 'wpe-templates', [ $this, 'wpt_render_plugin_settings_page'] );
	}

	public function wpt_render_plugin_settings_page() {
		$post_obj = new WPT_Pages_List();
		?>
		<div class="wrap">
			<h2>WPT Templates</h2>
			<?php $post_obj->prepare_items(); ?>
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<form id="posts-filter" method="get">
								<?php
								$post_obj->display();
								?>
							</form>
						</div>
					</div>
				</div>
<!--				<br class="clear">-->
			</div>
		</div>
		<?php
	}
}
