<?php

if ( ! class_exists( 'WPT_WP_List_Table' ) ) {
	require_once( dirname( __FILE__ ) . "/class-wp-list-table.php" );
}


class WPT_Pages_List extends WPT_WP_List_Table {

	public function get_columns() {}
	public function prepare_items() {}

}
