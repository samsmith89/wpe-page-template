<?php

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
//		add_action( 'wp_footer', [ $this, 'my_standard_settings' ] );
	}

	/**
	 * @return mixed
	 */
	public function my_standard_settings() {
		echo "working";
	}
}
