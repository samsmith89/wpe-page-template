<?php

namespace WPT;

/**
 * Creates the additional column on admin pages for page template name
 *
 * @since 1.0.0
 *
 */

class Admin {

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
		$page_template = get_page_template_slug();
		$templates = wp_get_theme()->get_page_templates($post->ID, 'page' );

		if ( $page_template ) {
			foreach ( $templates as $slug => $template ) {
				if ( ( $page_template === $slug ) ) {
					echo $template;
				}
			}
		} else {
			echo "Default Page Template";
		}
	}
}

