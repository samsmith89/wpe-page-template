<?php
/**
 * Plugin Name:     WPE Page Template
 * Description:     Adds organization to page template files.
 * Author:          Sam Smith
 * Author URI:      https://gsamsmith.com
 * Text Domain:     wpt
 * Domain Path:     /languages
 * Version:         1.0.0
 *
 * @package         Wpe_Page_Template
 */

class WPT {

	protected static $_instance;

	protected static $_version = '1.0.0';

	public static function get_instance() {
		if ( ! self::$_instance instanceof self ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	protected function __construct() {
		add_action( 'plugins_loaded', array( $this, 'maybe_setup' ), - 9999 );
	}

	/**
	 * Includes the appropriate files, instances, and calls the needed classes.
	 *
	 * @since 1.0.0
	 */

	protected function includes() {
		require_once( $this->get_plugin_dir() . 'includes/lib/autoloader.php' );

		WPT\Includes\Sort::get_instance();
		WPT\Includes\Settings::get_instance();
	}

	/**
	 * Calls the includes and actions methods
	 *
	 * @since 1.0.0
	 */

	public function maybe_setup() {

		$this->includes();
		$this->actions();

	}

	/**
	 * Calls the actions needed for enqueuing scripts and styles
	 *
	 * @since 1.0.0
	 */

	protected function actions() {
		add_action( 'admin_enqueue_scripts', array( $this, 'styles' ) );
	}

	/**
	 * Handles enqueuing styles
	 *
	 * @since 1.0.0
	 */

	public function styles() {
		wp_enqueue_style( $this->get_id() . '-styles', $this->get_plugin_url() . '/assets/css/admin-styles.css', array(), $this->get_version() );
	}

	/**
	 * Gets the plugin URL
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */

	public function get_plugin_url() {
		return plugin_dir_url( $this->get_plugin_file() );
	}

	/**
	 * Gets the plugin directory
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */

	public function get_plugin_dir() {
		return plugin_dir_path( $this->get_plugin_file() );
	}

	/**
	 * Gets the plugin filepath
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */

	public function get_plugin_file() {
		return __FILE__;
	}

	/**
	 * Returns the version of the plugin
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */

	public function get_version() {
		return self::$_version;
	}

	/**
	 * Returns the plugin ID. Used in the textdomain
	 *
	 * @return string
	 * @since  1.0.0
	 *
	 */
	public static function get_id() {
		return 'wpt';
	}

}

/**
 * Instantiates the class
 *
 * @since 1.0.0
 *
 * @return object
 */

function WPT() {
	return WPT::get_instance();
}
WPT();
