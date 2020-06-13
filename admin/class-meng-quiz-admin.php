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
			'meng_blanks_basic' => [
				'label' => 'Blanks Basic',
				'slug' => 'basic_blanks'
			],
			'meng_blanks_cols' => [
				'label' => 'Blanks Columns',
				'slug' => 'cols_blanks'
			],
			'meng_multi_selector' => [
				'label' => 'Multi Selectors',
				'slug' => 'multi_selector'
			],
			'meng_true_false' => [
				'label' => 'True / False',
				'slug' => 'meng_true_false'
			]
		];

		foreach( $quiz_types as $id => $args ) {
			register_post_type($id, [
				'labels'						=> 		['name' => $args['label']],
				'public'             	=> 		true,
				'publicly_queryable' 	=> 		true,
				'show_ui'            	=> 		true,
				'show_in_menu'       	=> 		true,
				'query_var'          	=> 		true,
				'rewrite'            	=> 		[ 'slug' => $args['slug'] ],
				'capability_type'    	=> 		'post',
				'has_archive'        	=> 		true,
				'hierarchical'       	=> 		false,
				'menu_icon'					=>			'dashicons-lightbulb',
				'menu_position'      	=> 		null,
				'supports'           	=> 		[ 'title', 'editor', 'thumbnail' ],
			]);
		}

	}

	/**
	 * Create and populate custom columns: Shortcode and Count
	 *
	 * @param string $func
	 * @param array $args
	 * @return mixed
	 */
	public static function __callStatic($func, $args)
	{
		$post_columns = [
			'meng_sortables_basic_post_columns', // hooked function_name
			'meng_mcqs_basic_post_columns',
			'meng_mcqs_cloze_post_columns',
			'meng_blanks_cols_post_columns',
			'meng_multi_selector_post_columns',
			'meng_true_false_post_columns'
		];
		$column_id = array_search($func, $post_columns);
		if( $column_id !== false ) {
			$columns = $args[0];
			return [
				'cb' => $columns['cb'],
				'title' => $columns['title'],
				'meng_count' => apply_filters('meng_admin_post_column_meng_count_label', 'No. of ' . explode('_', $post_columns[$column_id])[1], $post_columns[$column_id]),
				'meng_shortcode' => 'Shortcode',
				'date' => $columns['date']
			];
		}

		$custom_columns = [
			'meng_mcqs_basic_post_custom_column' => 'meng_mcqs', // hooked function_name => post_meta_field_id
			'meng_mcqs_cloze_post_custom_column' => 'meng_mcqs_cloze',
			'meng_sortables_basic_post_custom_column' => 'meng_sortables',
			'meng_blanks_cols_post_custom_column' => 'meng_blanks_cols',
			'meng_multi_selector_post_custom_column' => 'meng_multi_selector',
			'meng_true_false_post_custom_column' => 'meng_true_false'
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

	public static function admin_menu_page()
	{
		add_menu_page(
			__('Meng Quizer', 'meng'),
			__('Meng Quizer', 'meng'),
			'manage_options',
			'meng_quizer',
			function() {
				require MENG_QUIZ_DIR . '/admin/partials/meng-quizer-menu.php';
			}
		);
	}

}