<?php
namespace WPT\Includes;

use WPT;

/**
 * Creates the additional column on admin pages for page template name
 *
 * @since 1.0.0
 */

class Sort {

	protected static $_instance;

	public static function get_instance() {
		if ( ! self::$_instance instanceof self ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {
		add_filter( 'query_vars', [ $this, 'wpt_query_vars_filter' ] );
		add_action( 'pre_get_posts', [ $this, 'wpt_alter_query' ] );
		add_filter( 'manage_page_posts_columns', [ $this, 'add_columns_page_template' ] );
		add_action( 'manage_page_posts_custom_column', [ $this, 'wpt_columns_page_template_data' ], 10, 2 );
	}

	/**
	 * Adds the "wpt" query variable.
	 *
	 * @since 1.0.0
	 *
	 * @see "apply_filters( 'query_vars', string[] $public_query_vars )"
	 * @link https://developer.wordpress.org/reference/hooks/query_vars/
	 *
	 * @param array $vars Query Vars.
	 * @return array $vars Modified Query Vars.
	 */

	public function wpt_query_vars_filter( $vars ) {
		$vars[] .= 'wpt';
		return $vars;
	}

	/**
	 * Alters the query before it's sent.
	 *
	 * @since 1.0.0
	 *
	 * @see "do_action_ref_array( 'pre_get_posts', WP_Query $query )"
	 * @link https://developer.wordpress.org/reference/hooks/pre_get_posts/
	 *
	 * @param array $query Query array.
	 */

	public function wpt_alter_query( $query ) {

		if ( !is_admin() || 'page' != $query->query['post_type'] )
			return;

		if ( ($query->query_vars['wpt']) && ($query->query_vars['wpt'] !== 'default') ) {
			$query->set( 'meta_key', '_wp_page_template' );
			$query->set( 'meta_value', $query->query_vars['wpt'] );
		}

		if ( ($query->query_vars['wpt']) && ($query->query_vars['wpt'] === 'default') ) {
			$query->set( 'meta_query', array(
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
			) );
		}
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

	public function add_columns_page_template( $columns ) {

		return array(
			'cb' => $columns['cb'],
			'title' => $columns['title'],
			'author' => $columns['author'],
			'page_template' => __( 'Page Template', WPT::get_id() ),
			'comments' => $columns['comments'],
			'date' => $columns['date']
		);

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

	public function wpt_columns_page_template_data( $column, $post_id ) {
		if ( $column === 'page_template') {
			$page_template = get_page_template_slug();
			$templates     = wp_get_theme()->get_page_templates( $post_id, 'page' );

			if ( $page_template ) {
				foreach ( $templates as $slug => $template ) {
					if ( ( $page_template === $slug ) ) {
						echo '<a href="' . add_query_arg( 'wpt', $slug ) . '">' . $template . "</a>";
					}
				}
			} else {
				echo '<a href="' . add_query_arg( 'wpt', 'default' ) . '">' . __( "Default Page Template", WPT::get_id() ) . "</a>";
			}
		}
	}
}

