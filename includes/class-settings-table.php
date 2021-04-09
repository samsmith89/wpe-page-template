<?php

if ( ! class_exists( 'WPT_WP_List_Table' ) ) {
	require_once( dirname( __FILE__ ) . "/class-wp-list-table.php" );
}

class WPT_Pages_List extends WPT_WP_List_Table {

	public function prepare_items() {

		$search_term = isset($_POST['s']) ? trim($_POST['s']) : "";

		$this->items = $this->wpt_list_table_data($search_term);

		$columns = $this->get_columns();

		$this->_column_headers = array( $columns );

	}

	public function wpt_list_table_data($search_term = '') {

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

				if ( ! empty( $search_term ) ) {
					$args['s'] = $search_term;
				}
				$the_query = new WP_Query( $args );

				$templates_array[] = array(
					"number" => $count,
					"title"  => $template,
					"slug"   => $slug ,
					"pages"  => '<a href="/wp-admin/edit.php?post_type=page&wpt=' . $slug  . '">' . $the_query->found_posts . '</a>'
				);
				wp_reset_postdata();
				$count ++;
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
				"title"  => __( 'Default Template', 'wpt' ),
				"slug"   => "page.php",
				"pages"  => '<a href="/wp-admin/edit.php?post_type=page&wpt=default">' . $the_query->found_posts . '</a>'
			);
			wp_reset_postdata();
		}

		return $templates_array;
	}

	public function get_columns() {

		return array(
			"number" => "Number",
			"title"  => "Title",
			"slug"   => "Slug",
			"pages"  => "Pages"
		);

	}

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
