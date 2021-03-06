<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Meng_Quiz_Public
{
	/**
	 * Enqueues public stylesheets
	 *
	 * @return void
	 */
	public static function enqueue_styles()
	{
		$dir = MENG_QUIZ_URI . '/public/assets/css/';

		// Main css public file
		wp_enqueue_style('meng_public_style', $dir . 'meng_public_style.min.css', [], MENG_VERSION);
	}

	/**
	 * Enqueues public javascript files
	 *
	 * @return void
	 */
	public static function enqueue_scripts()
	{
		$dir = MENG_QUIZ_URI . '/public/assets/js/';
		
		// Main script file
		wp_enqueue_script('meng_public_script', $dir . 'meng_public_script.js', ['jquery'], MENG_VERSION);

		// Ajax object for main file
		wp_localize_script('meng_public_script', 'ajaxObject', [
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'security' => wp_create_nonce( 'my-special-string' ),
			'plugin_url' => MENG_QUIZ_URI
		]);

		// Main script file
		wp_enqueue_script('meng_public_script_basic', $dir . 'meng_public_script_basic.js', ['jquery'], MENG_VERSION);
		
		// jQuery-UI script
		wp_enqueue_script('jquery-ui', $dir . 'jquery-ui.min.js', ['jquery']);
	}

	/**
	 * Ajax action callback for Basic Mcqs
	 * 
	 * Sends json of correct options of excercise
	 *
	 * @return void
	 */
	public static function meng_ajax_action_meng_mcqs_basic()
	{
		check_ajax_referer('my-special-string', 'security');
		$ex_id = (int) $_POST['postId'];
		$excercise = get_post_meta($ex_id, 'meng_mcqs', true);
		echo json_encode($excercise);
		die();
	}

	/**
	 * Ajax action callback for Cloze Mcqs
	 * 
	 * Sends json of cloze mcqs meta of excercise
	 *
	 * @return void
	 */
	public function meng_ajax_action_meng_mcqs_cloze()
	{
		check_ajax_referer('my-special-string', 'security');
		$ex_id = (int) $_POST['postId'];
		$excercise = get_post_meta($ex_id, 'meng_mcqs_cloze', true);
		echo json_encode($excercise);
		die();
	}

	/**
	 * Ajax action callback for Blanks Basic
	 * 
	 * Sends json of blanks basic meta of excercise
	 *
	 * @return void
	 */
	public function meng_ajax_action_meng_blanks_basic()
	{
		check_ajax_referer('my-special-string', 'security');
		$ex_id = (int) $_POST['postId'];
		$excercise = get_post_meta($ex_id, 'meng_blanks_basic', true);
		echo json_encode($excercise);
		die();
	}

	/**
	 * Ajax action callback for Blanks cols
	 * 
	 * Sends json of blanks cols meta of excercise
	 *
	 * @return void
	 */
	public function meng_ajax_action_meng_blanks_cols()
	{
		check_ajax_referer('my-special-string', 'security');
		$ex_id = (int) $_POST['postId'];
		$excercise = get_post_meta($ex_id, 'meng_blanks_cols', true);
		echo json_encode($excercise);
		die();
	}

	/**
	 * Ajax action callback for Blanks cols
	 * 
	 * Sends json of blanks cols meta of excercise
	 *
	 * @return void
	 */
	public function meng_ajax_action_meng_multi_selector()
	{
		check_ajax_referer('my-special-string', 'security');
		$ex_id = (int) $_POST['postId'];
		$excercise = get_post_meta($ex_id, 'meng_multi_selector', true);
		echo json_encode($excercise);
		die();
	}

	/**
	 * Ajax action callback for Blanks cols
	 * 
	 * Sends json of blanks cols meta of excercise
	 *
	 * @return void
	 */
	public function meng_ajax_action_meng_true_false()
	{
		check_ajax_referer('my-special-string', 'security');
		$ex_id = (int) $_POST['postId'];
		$excercise = get_post_meta($ex_id, 'meng_true_false', true);
		echo json_encode($excercise);
		die();
	}

	/**
	 * Callback for shortcode: meng_mcqs_basic
	 *
	 * @param array $atts
	 * @return string
	 */
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

	/**
	 * Callback for shortcode: meng_sortables_basic
	 *
	 * @param array $atts
	 * @return string
	 */
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

	/**
	 * Callback for shortcode: meng_mcqs_cloze
	 *
	 * @param array $atts
	 * @return string
	 */
	public static function meng_mcqs_cloze_shortcode_callback($atts)
	{
		$atts = shortcode_atts([
			'id' => 0
		], $atts, 'meng_mcqs_cloze');

		ob_start();

		require MENG_QUIZ_DIR . 'public/templates/meng-mcqs-cloze.php';

		$output = ob_get_clean();
		return $output;
	}
	
	/**
	 * Callback for shortcode: meng_mcqs_cloze
	 *
	 * @param array $atts
	 * @return string
	 */
	public static function meng_blanks_basic_shortcode_callback($atts)
	{
		$atts = shortcode_atts([
			'id' => 0
		], $atts, 'meng_blanks_basic');

		ob_start();

		require MENG_QUIZ_DIR . 'public/templates/meng-blanks-basic.php';

		$output = ob_get_clean();
		return $output;
	}

	public static function meng_blanks_cols_shortcode_callback($atts)
	{
		$atts = shortcode_atts([
			'id' => 0
		], $atts, 'meng_blanks_cols');

		ob_start();

		require MENG_QUIZ_DIR . 'public/templates/meng-blanks-cols.php';

		$output = ob_get_clean();
		return $output;
	}

	public static function meng_multi_selector_shortcode_callback($atts)
	{
		$atts = shortcode_atts([
			'id' => 0
		], $atts, 'meng_multi_selector');

		ob_start();

		require MENG_QUIZ_DIR . 'public/templates/meng-multi-selector.php';

		$output = ob_get_clean();
		return $output;
	}

	public static function meng_true_false_shortcode_callback($atts)
	{
		$atts = shortcode_atts([
			'id' => 0,
			'layout' => 'simple'
		], $atts, 'meng_true_false');

		ob_start();

		require MENG_QUIZ_DIR . 'public/templates/meng-true-false.php';

		$output = ob_get_clean();
		return $output;
	}

}