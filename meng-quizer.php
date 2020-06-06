<?php
/**
 * MENG Quizer
 *
 * @package     MengQuiz
 * @author      Sharjeel Ahmad
 * @copyright   2020 Sharjeel Ahmad
 * @license     GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: MENG Quizer
 * Plugin URI:  
 * Description: This plugin includes different types of quizes like: Mcqs, Sortables which can be shown via shortcodes
 * Version:     1.0.0
 * Author:      Sharjeel Ahmad
 * Author URI:  https://github.com/leo8545
 * Text Domain: meng
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Constants
define('MENG_VERSION', "1.0.0"); // Plugin version
define('MENG_QUIZ_URI', plugin_dir_url(__FILE__)); // Plugin uri
define('MENG_QUIZ_DIR', plugin_dir_path(__FILE__)); // Plugin dir

/**
 * Main plugin's class
 * @since 1.0.0
 */
final class Meng_Quizer
{
	public function __construct()
	{
		register_activation_hook(__FILE__, [$this, 'activate']);
		register_deactivation_hook(__FILE__, [$this, 'deactivate']);

		$this->load_dep();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Activator on plugin activation
	 *
	 * @return void
	 */
	public function activate()
	{
		update_option('rewrite_rules', '');
	}

	/**
	 * Deactivator on plugin activation
	 *
	 * @return void
	 */
	public function deactivate() {}

	/**
	 * Load dependencies
	 * 
	 * Includes files
	 *
	 * @return void
	 */
	public function load_dep()
	{
		require MENG_QUIZ_DIR . 'admin/class-meng-quiz-admin.php';
		require MENG_QUIZ_DIR . 'admin/class-meng-quiz-admin-metabox.php';
		require MENG_QUIZ_DIR . 'public/class-meng-quiz-public.php';
	}

	/**
	 * Defines all public side hooks
	 *
	 * @return void
	 */
	public function define_public_hooks()
	{
		$public = new Meng_Quiz_Public;
		add_action('wp_enqueue_scripts', [$public, 'enqueue_styles']);
		add_action('wp_enqueue_scripts', [$public, 'enqueue_scripts']);
		add_action('wp_ajax_my_action', [$public, 'meng_ajax_action']);
		// Shortcodes
		add_shortcode('meng_mcqs_basic', [$public, 'meng_mcqs_basic_shortcode_callback']);
		add_shortcode('meng_sortables_basic', [$public, 'meng_sortables_basic_shortcode_callback']);
	}

	/**
	 * Defines all admin side hooks
	 *
	 * @return void
	 */
	public function define_admin_hooks()
	{
		$admin = new Meng_Quiz_Admin;
		add_action('admin_enqueue_scripts', [$admin, 'enqueue_styles']);
		add_action('admin_enqueue_scripts', [$admin, 'enqueue_scripts']);
		add_action('init', [$admin, 'register_post_types']);
		add_filter('manage_meng_sortables_basic_posts_columns', [$admin, 'meng_sortables_basic_post_columns'], 10, 1);
		add_action('manage_meng_sortables_basic_posts_custom_column', [$admin, 'meng_sortables_basic_post_custom_column'], 10, 2);
		add_filter('manage_meng_mcqs_basic_posts_columns', [$admin, 'meng_mcqs_basic_post_columns'], 10, 1);
		add_action('manage_meng_mcqs_basic_posts_custom_column', [$admin, 'meng_mcqs_basic_post_custom_column'], 10, 2);
		// Class: Metabox
		add_action('add_meta_boxes', ['Meng_Quiz_Admin_Metabox', 'add']);
		add_action('save_post', ['Meng_Quiz_Admin_Metabox', 'save']);
	}

	/**
	 * Set locale
	 *
	 * @return void
	 */
	public function set_locale()
	{
		load_plugin_textdomain('meng', false, ISHA_TEST_DIR . '/languages/');
	}

}

new Meng_Quizer;