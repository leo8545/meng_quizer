<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Meng_Quiz_Public
{
	public static function enqueue_styles()
	{
		$dir = MENG_QUIZ_URI . '/public/assets/css/';
		wp_enqueue_style('meng_public_style', $dir . 'meng_public_style.min.css', [], MENG_VERSION);
	}

	public static function enqueue_scripts()
	{
		$dir = MENG_QUIZ_URI . '/public/assets/js/';
		wp_enqueue_script('meng_public_script', $dir . 'meng_public_script.js', ['jquery'], MENG_VERSION);
		wp_localize_script('meng_public_script', 'ajaxObject', [
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'security' => wp_create_nonce( 'my-special-string' )
		]);
		wp_enqueue_script('jquery-ui', $dir . 'jquery-ui.min.js', ['jquery']);
	}

	public static function meng_ajax_action()
	{
		check_ajax_referer( 'my-special-string', 'security' );
		$serialized = $_POST['serialized'];
		$ex_id = $_POST["exId"];
		$ex = get_post_meta((int) $ex_id,'meng_mcqs',true);
		$result = [];
		foreach($ex as $k => $e) {
			$result[$k] = $e['options']['correct']; // All mcqs with their number => 'answer
		}
		echo json_encode($result);
		die();
	}

	public static function meng_mcqs_basic_shortcode_callback($atts)
	{
		$atts = shortcode_atts([
			'id' => 0,
			'layout' => 'simple'
		], $atts, 'meng_mcqs_basic');
		ob_start();

		require MENG_QUIZ_DIR . 'public/templates/meng-mcqs-basic.php';

		$output = ob_get_clean();
		return $output;
	}

	public static function meng_sortables_basic_shortcode_callback($atts)
	{
		$atts = shortcode_atts([
			'id' => 0,
			'layout' => 'simple'
		], $atts, 'meng_sortables_basic');

		ob_start();

		require MENG_QUIZ_DIR . 'public/templates/meng-sortables-basic.php';

		$output = ob_get_clean();
		return $output;
	}
}