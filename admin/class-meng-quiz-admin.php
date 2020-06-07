<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Meng_Quiz_Admin
{
	public function enqueue_styles()
	{
		$dir = MENG_QUIZ_URI . "/admin/assets/css/";
		wp_enqueue_style("meng_admin_style", $dir . "meng_admin_style.min.css", [], MENG_VERSION);
	}
	
	public function enqueue_scripts()
	{
		$dir = MENG_QUIZ_URI . "/admin/assets/js/";
		wp_enqueue_script('meng_admin_script', $dir. 'main.js', ['jquery'], MENG_VERSION);
	}

	public function register_post_types()
	{
		$quiz_types = [
			'meng_mcqs_basic' => [
				'label' => 'MCQs Basic',
				'slug' => 'basic_mcqs'
			],
			'meng_mcqs_cloze' => [
				'label' => 'MCQs Cloze',
				'slug' => 'cloze_mcqs'
			],
			'meng_sortables_basic' => [
				'label' => 'Sortables Basic',
				'slug' => 'basic_sortables'
			],
		];

		foreach( $quiz_types as $id => $args ) {
			register_post_type($id, [
				'labels'				=> 		['name' => $args['label']],
				'public'             	=> 		true,
				'publicly_queryable' 	=> 		true,
				'show_ui'            	=> 		true,
				'show_in_menu'       	=> 		true,
				'query_var'          	=> 		true,
				'rewrite'            	=> 		[ 'slug' => $args['slug'] ],
				'capability_type'    	=> 		'post',
				'has_archive'        	=> 		true,
				'hierarchical'       	=> 		false,
				'menu_position'      	=> 		null,
				'supports'           	=> 		[ 'title', 'editor', 'thumbnail' ],
			]);
		}

	}

	public static function __callStatic($func, $args)
	{
		$post_columns = [
			'meng_sortables_basic_post_columns',
			'meng_mcqs_basic_post_columns',
			'meng_mcqs_cloze_post_columns'
		];
		$column_id = array_search($func, $post_columns);
		if( $column_id !== false ) {
			$columns = $args[0];
			return [
				'cb' => $columns['cb'],
				'title' => $columns['title'],
				'meng_count' => 'No. of ' . explode('_', $post_columns[$column_id])[1],
				'meng_shortcode' => 'Shortcode',
				'date' => $columns['date']
			];
		}

		$custom_columns = [
			'meng_mcqs_basic_post_custom_column' => 'meng_mcqs', // function_name => post_meta_field_id
			'meng_mcqs_cloze_post_custom_column' => 'meng_mcqs_cloze',
			'meng_sortables_basic_post_custom_column' => 'meng_sortables',
		];
		$custom_column_index = array_search($func, array_keys($custom_columns));
		if( $custom_column_index !== false ) {
			$col = $args[0];
			$post_id = (int) $args[1];
			if( $col == 'meng_count' ) {
				$field_id = array_values($custom_columns)[(int) $custom_column_index];
				$meta = get_post_meta($post_id, $field_id, true);
				echo is_array($meta) ? count($meta) : 0;
			}
			if( $col == 'meng_shortcode' ) {
				$arr = explode("_", array_keys($custom_columns)[$custom_column_index]);
				echo '[' . $arr[0] . '_' . $arr[1] . '_' . $arr[2] . ' id="' . $post_id . '"]';
			}
			return;
		}
	}


}