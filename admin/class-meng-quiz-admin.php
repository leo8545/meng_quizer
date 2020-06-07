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

	public static function meng_sortables_basic_post_columns($columns)
	{
		return [
			'cb' => $columns['cb'],
			'title' => $columns['title'],
			'sortables_count' => 'No. of Sortables',
			'sortables_shortcode' => 'Shortcode',
			'date' => $columns['date']
		];
	}

	public static function meng_sortables_basic_post_custom_column($column, $post_id)
	{
		if($column === 'sortables_count') {
			$meng_sortables = get_post_meta($post_id, "meng_sortables", true);
			if(is_array($meng_sortables) && count($meng_sortables) > 0) {
				echo count($meng_sortables);
			} else {
				echo 0;
			}
		}
		if($column === 'sortables_shortcode') {
			echo "<pre>[meng_sortables_basic id=$post_id]</pre>";
		}
	}

	public static function meng_mcqs_basic_post_columns($columns)
	{
		return [
			'cb' => $columns['cb'],
			'title' => $columns['title'],
			'mcqs_count' => 'No. of MCQs',
			'mcqs_shortcode' => 'Shortcode',
			'date' => $columns['date']
		];
	}

	public static function meng_mcqs_basic_post_custom_column($column, $post_id)
	{
		if($column === 'mcqs_count') {
			$meng_mcqs = get_post_meta($post_id, "meng_mcqs", true);
			if(is_array($meng_mcqs) && count($meng_mcqs) > 0) {
				echo count($meng_mcqs);
			} else {
				echo 0;
			}
		}
		if($column === 'mcqs_shortcode') {
			echo "<pre>[meng_mcqs_basic id=$post_id]</pre>";
		}
	}


}