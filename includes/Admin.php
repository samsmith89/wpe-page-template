<?php

namespace WPT;

class Admin {

	protected static $_instance;

	public static function get_instance() {
		if ( ! self::$_instance instanceof Admin ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {
		add_action( 'wp_footer', [ $this, 'my_standard_settings' ] );
	}

	/**
	 * @return mixed
	 */
	public function my_standard_settings() {
		echo "working";
	}
}

