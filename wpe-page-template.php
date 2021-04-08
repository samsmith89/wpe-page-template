<?php
/**
 * Plugin Name:     Wpe Page Template
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     PLUGIN DESCRIPTION HERE
 * Author:          YOUR NAME HERE
 * Author URI:      YOUR SITE HERE
 * Text Domain:     wpe-page-template
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Wpe_Page_Template
 */

// Your code starts here.
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

	protected function includes() {
		require_once( $this->get_plugin_dir() . 'vendor/autoload.php' );

		WPT\Admin::get_instance();
	}

	public function maybe_setup() {
		if ( ! $this->check_required_plugins() ) {
			return;
		}

		$this->includes();
		$this->actions();
	}

	protected function actions() {
//        add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
//        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'styles' ) );
	}

	public function styles() {
		wp_enqueue_style( $this->get_id() . '-styles', $this->get_plugin_url() . '/assets/css/admin-styles.css', array(), $this->get_version() );
	}

	public function get_plugin_url() {
		return plugin_dir_url( $this->get_plugin_file() );
	}

	public function get_plugin_dir() {
		return plugin_dir_path( $this->get_plugin_file() );
	}

	public function get_plugin_file() {
		return __FILE__;
	}

	protected function check_required_plugins() {
		return true;
	}

	/**
	 * Return the version of the plugin
	 *
	 * @return string
	 * @since  1.0.0
	 *
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
	public function get_id() {
		return 'WPT';
	}

}

function WPT() {
	return WPT::get_instance();
}

WPT();
