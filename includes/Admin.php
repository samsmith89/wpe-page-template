<?php

namespace WPT;

/**
 * Creates the additional column on admin pages for page template name
 *
 * @since 1.0.0
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
		add_filter( 'manage_page_posts_columns', [ $this, 'columns_page_template' ] );
		add_action( 'manage_page_posts_custom_column', [ $this, 'columns_page_template_data' ], 10, 2 );
		add_filter( 'parse_query', [ $this, 'page_template_filter' ] );
	}

	/**
	 * Adds "Page Template" column to the pages admin
	 *
	 * @since 1.0.0
	 *
	 * @see "manage_{$post_type}_posts_columns hook"
	 * @link https://developer.wordpress.org/reference/hooks/manage_post_type_posts_columns/
	 *
	 * @param array $columns
	 * @return array Modified columns array.
	 */

	public function columns_page_template( $columns ) {
		$columns['page_template'] = 'Page Template';

		return $columns;
	}

	/**
	 * Populates the "Page Template" column with page template data
	 *
	 * @since 1.0.0
	 *
	 * @see "manage_{$post->post_type}_posts_custom_column"
	 * @link https://developer.wordpress.org/reference/hooks/manage_post-post_type_posts_custom_column/
	 *
	 * @param array $column
	 * @param int $post_id
	 */

	public function columns_page_template_data( $column, $post_id ) {
		$page_template = get_page_template_slug();
		$templates     = wp_get_theme()->get_page_templates( $post_id, 'page' );

		if ( $page_template ) {
			foreach ( $templates as $slug => $template ) {
				if ( ( $page_template === $slug ) ) {
					echo '<a href="' . add_query_arg( 'wpt', $slug ) . '">' . $template . "</a>";
				}
			}
		} else {
			echo '<a href="' . add_query_arg( 'wpt', 'default' ) . '">' . __( "Default Page Template", 'wpt' ) . "</a>";
		}
	}

	/**
	 * Modifies the query args for the pages admin
	 *
	 * @since 1.0.0
	 *
	 * @see "do_action_ref_array( 'parse_query', WP_Query $query )"
	 * @link https://developer.wordpress.org/reference/hooks/parse_query/
	 *
	 * @param array $query
	 * @return array $query Modified query array.
	 */

	public function page_template_filter( $query ) {
		if ( is_admin() and $query->query['post_type'] === 'page' ) {
			$qv               = &$query->query_vars;
			$qv['meta_query'] = array();

			if ( ! empty( $_GET['wpt'] ) ) {
				if ( $_GET['wpt'] === 'default' ) {
					$qv['meta_query'][] = array(
						'relation' => 'OR',
						array(
							'key'     => '_wp_page_template',
							'compare' => 'NOT EXISTS',
						),
						array(
							'key'     => '_wp_page_template',
							'value'   => $_GET['wpt'],
							'compare' => '=',
							'type'    => 'CHAR'
						),
					);
				} else {
					$qv['meta_query'][] = array(
						'key'     => '_wp_page_template',
						'value'   => $_GET['wpt'],
						'compare' => '=',
						'type'    => 'CHAR'
					);
				}
			}
		}
		return $query;
	}
}

