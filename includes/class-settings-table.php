<?php
namespace WPT\Includes;

use WP_Query;
use WPT;
use WPT\Includes\WP_List_Table as WP_List_Table;

/**
 * Extends the WP_List_Table class that's been copied from core per documentation recommendation:
 * https://developer.wordpress.org/reference/classes/wp_list_table/#developer-usage-private-status
 *
 * @since 1.0.0
 *
 * @see WP_List_Table
 * @link https://developer.wordpress.org/reference/classes/wp_list_table/
 */

class Settings_Table extends WP_List_Table {

	/**
	 * Overrides the prepare_items() in the WP_List_Table class
	 *
	 * @since 1.0.0
	 *
	 * @see WP_List_Table
	 */

	public function prepare_items() {

		$this->items = $this->wpt_list_table_data();

		$columns = $this->get_columns();

		$this->_column_headers = array( $columns );

	}

	/**
	 * Generates the data for the WP_List_Table to use
	 *
	 * @since 1.0.0
	 *
	 * @return array $templates_array Returned array of templates for the theme.
	 */

	public function wpt_list_table_data() {

		if ( false === ( $db_count_posts = get_transient( 'wpt-page-count' ) ) ) {
			$db_count_posts = wp_count_posts('page');
			$db_count_posts_int = 0;
			foreach ( $db_count_posts as $val) {
				$db_count_posts_int = $db_count_posts_int + intval($val);
			}
			set_transient( 'wpt-page-count', $db_count_posts_int, MONTH_IN_SECONDS );
		}

		$db_count_posts = wp_count_posts('page');
		$post_count_actual = 0;
		foreach ( $db_count_posts as $val) {
			$post_count_actual = $post_count_actual + intval($val);
		}

		if ( ( false === ( $templates_array = get_transient( 'wpt-page-templates' ) ) ) || ( $db_count_posts !== $post_count_actual ) ) {
			$templates_array = array();
			$all_templates = get_page_templates( null, 'page' );

			if ( $all_templates ) {

				$count = 1;
				foreach ( $all_templates as $template => $slug ) {
					$args          = array(
						'post_type' => 'page',
						'meta_key'   => '_wp_page_template',
						'meta_value' => $slug
					);
					$the_query = new WP_Query( $args );

					$templates_array[] = array(
						"number" => $count,
						"title"  => $template,
						"slug"   => $slug ,
						"pages"  => '<a href="/wp-admin/edit.php?post_type=page&wpt=' . $slug  . '">' . $the_query->found_posts . '</a>'
					);
					wp_reset_postdata();
					$count ++;

					if ($count >= 40) {
						break;
					}
				}

				$args = array(
					'post_type'  => 'page',
					'meta_query' => array(
						'relation' => 'OR',
						array(
							'key'     => '_wp_page_template',
							'value'   => '',
							'compare' => 'NOT EXISTS',
						),
						array(
							'key'     => '_wp_page_template',
							'value'   => 'default',
							'compare' => '=',
							'type'    => 'CHAR'
						),
					)
				);
				$the_query     = new WP_Query( $args );
				$templates_array[] = array(
					"number" => $count,
					"title"  => __( 'Default Template', WPT::get_id() ),
					"slug"   => "page.php",
					"pages"  => '<a href="/wp-admin/edit.php?post_type=page&wpt=default">' . $the_query->found_posts . '</a>'
				);
				wp_reset_postdata();
			}
			set_transient( 'wpt-page-templates', $templates_array, MONTH_IN_SECONDS );
		}

		return $templates_array;
	}

	/**
	 * Overrides the get_columns function in the parent class needed for organizing the template data
	 *
	 * @since 1.0.0
	 *
	 * @link URL
	 *
	 * @return array Modified columns.
	 */

	public function get_columns() {

		return array(
			"number" => "Number",
			"title"  => "Title",
			"slug"   => "Slug",
			"pages"  => "Pages"
		);

	}

	/**
	 * Overrides the default columns function in the parent class needed for organizing the template data
	 *
	 * @since 1.0.0
	 *
	 * @link URL
	 *
	 * @return array|int of Modified columns or "0".
	 */

	public function column_default( $item, $column_name ) {

		switch ( $column_name ) {
			case 'number':
			case 'title':
			case 'slug':
			case 'pages':
				return $item[ $column_name ];
		}
	}

}
