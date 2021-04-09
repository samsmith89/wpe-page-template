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

//		$per_page = 3;
//		$current_page = $this->get_pagenum();
//		if ( 1 < $current_page ) {
//			$offset = $per_page * ( $current_page - 1 );
//		} else {
//			$offset = 0;
//		}

		$this->items = $this->wpt_list_table_data();

		$columns = $this->get_columns();

		$this->_column_headers = array( $columns );

		// Set the pagination
//		$this->set_pagination_args( array(
//			'total_items' => count($this->items),
//			'per_page' => $per_page,
//			'total_pages' => ceil( count($this->items) / $per_page )
//		) );

	}

	/**
	 * Generates the data for the WP_List_Table to use
	 *
	 * @since 1.0.0
	 *
	 * @return array $templates_array Returned array of templates for the theme.
	 */

	public function wpt_list_table_data() {

		$all_templates = get_page_templates( null, 'page' );

		$templates_array = array();

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
			default:
				return "0";
		}
	}

}
