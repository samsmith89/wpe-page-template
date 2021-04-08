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
		add_filter('manage_page_posts_columns', [ $this, 'columns_cars' ] );
		add_action('manage_page_posts_custom_column' , [ $this, 'columns_cars_data' ], 10, 2);
		add_filter( 'parse_query',[ $this, 'bs_event_table_filter' ] );
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
					echo '<a href"' . add_query_arg( 'wpt', $slug ). '">' . $template . "</a>";
				}
			}
		} else {
			echo "Default Page Template";
		}
	}
	function columns_cars($columns) {
		$columns['page_template'] = 'Page Template';
		return $columns;
	}

	function columns_cars_data($column, $post_id) {
		$page_template = get_page_template_slug();
		$templates = wp_get_theme()->get_page_templates($post_id, 'page' );

		if ( $page_template ) {
			foreach ( $templates as $slug => $template ) {
				if ( ( $page_template === $slug ) ) {
					echo '<a href="' . add_query_arg( 'wpt', $slug ). '">' . $template . "</a>";
				}
			}
		} else {
			echo "Default Page Template";
		}
	}

	public function bs_event_table_filter( $query ) {
		if( is_admin() AND $query->query['post_type'] == 'page' ) {
			$qv = &$query->query_vars;
			$qv['meta_query'] = array();

			if( !empty( $_GET['wpt'] ) ) {
				$qv['meta_query'][] = array(
					'field' => '_wp_page_template',
					'value' => $_GET['wpt'],
					'compare' => '=',
					'type' => 'CHAR'
				);
			}
		}
	}
}

