<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Meng_Quiz_Admin_Metabox
{
	public static function add()
	{
		add_meta_box(
			'meng_mcqs_basic',
			__( 'Mcqs Basic', 'meng' ),
			function() {
				require MENG_QUIZ_DIR . '/admin/partials/meng-metabox-mcqs.php';
			},
			'meng_mcqs_basic'
		);

		add_meta_box(
			'meng_mcqs_basic_helper',
			__( 'Shortcode', 'meng' ),
			function($post) { ?>
				<div class="meng_shortcode">
					<strong>[meng_mcqs_basic id="<?php echo $post->ID ?>" layout="simple"]</strong>
				</div> <?php
			},
			'meng_mcqs_basic',
			'side',
			'high'
		);

		add_meta_box(
			'meng_sortables_basic',
			__( 'Sortables Basic', 'meng' ),
			function() {
				require MENG_QUIZ_DIR . '/admin/partials/meng-metabox-sortables-basic.php';
			},
			'meng_sortables_basic'
		);

		add_meta_box(
			'meng_sortables_basic_helper',
			__( 'Shortcode', 'meng' ),
			function($post) { ?>
				<div class="meng_shortcode"><strong>[meng_sortables_basic id="<?php echo $post->ID ?>" layout="simple"]</strong></div> <?php
			},
			'meng_sortables_basic',
			'side',
			'high'
		);

		add_meta_box(
			'meng_mcqs_cloze',
			__( 'MCQs Cloze', 'meng' ),
			function() {
				require MENG_QUIZ_DIR . '/admin/partials/meng-metabox-mcqs-cloze.php';
			},
			'meng_mcqs_cloze'
		);

		add_meta_box(
			'meng_blanks_basic',
			__('Blanks Basic', 'meng'),
			function() {
				require MENG_QUIZ_DIR . '/admin/partials/meng-metabox-blanks-basic.php';
			},
			'meng_blanks_basic'
		);

		add_meta_box(
			'meng_blanks_basic_helper',
			__('Shortcode', 'meng'),
			function($post) { ?>
				<div class="meng_shortcode"><strong>[meng_blanks_basic id="<?php echo $post->ID ?>" layout="simple"]</strong></div> <?php
			},
			'meng_blanks_basic',
			'side',
			'high'
		);

		// Blanks cols
		add_meta_box(
			'meng_blanks_cols',
			__('Blanks Columns', 'meng'),
			function() {
				require MENG_QUIZ_DIR . '/admin/partials/meng-metabox-blanks-cols.php';
			},
			'meng_blanks_cols'
		);

		// Multi selectors
		add_meta_box(
			'meng_multi_selector',
			__('Multi Selectors Question Box', 'meng'),
			function() {
				require MENG_QUIZ_DIR . '/admin/partials/meng-metabox-multi-selector.php';
			},
			'meng_multi_selector'
		);
	}

	public static function save($post_id)
	{
		if( array_key_exists('meng_mcqs', $_POST) ) {
			$meng_mcqs = $_POST['meng_mcqs'];
			$valid_mcqs = [];
			$counter = 0;
			foreach( $meng_mcqs as $mcq ) {
				$counter++;
				if( !empty($mcq['statement']) && !empty($mcq['options']) ) {
					$valid_mcqs[$counter]['statement'] = htmlspecialchars( $mcq['statement'] );
					$valid_mcqs[$counter]['options']['string'] = $mcq['options'];
					foreach( explode( "|", $mcq['options'] ) as $_index => $_option ) {
						$valid_mcqs[$counter]['options']['array'][] = trim($_option);
						if( strpos($_option, ":correct") !== false ) {
							$valid_mcqs[$counter]['options']['correct'] = trim( explode(":correct", $_option)[0] );
						}
					}

				}
			}
			update_post_meta($post_id, 'meng_mcqs', $valid_mcqs);
		}

		if(array_key_exists('meng_sortables', $_POST)) {
			$meng_sortables = $_POST['meng_sortables'];
			$valid_sortables = [];
			$counter = 0;
			foreach($meng_sortables as $field) {
				$counter++;
				if(!empty($field['static']) && !empty($field['dynamic'])) {
					$valid_sortables[$counter]['static'] = htmlspecialchars($field['static']);
					$valid_sortables[$counter]['dynamic'] = htmlspecialchars($field['dynamic']);
				}
			}
			update_post_meta($post_id, 'meng_sortables', $valid_sortables);
		}

		if( array_key_exists('meng_mcqs_cloze', $_POST) ) {
			$meng_mcqs_cloze = $_POST['meng_mcqs_cloze'];
			$valid_cloze = [];
			foreach( $meng_mcqs_cloze as $_option => $cloze ) {
				$valid_cloze[$_option]['options']['string'] = $cloze['options'];
				$valid_cloze[$_option]['description'] = $cloze['description'];
				foreach( explode("|", $cloze['options']) as $_index => $__option ) {
					$valid_cloze[$_option]['options']['array'][] = trim($__option);
					if( strpos($__option, ":correct") !== false ) {
						$valid_cloze[$_option]['options']['correct'] = trim( explode(":correct", $__option)[0] );
					}
				}
			}
			update_post_meta($post_id, 'meng_mcqs_cloze', $valid_cloze);
		}

		if(array_key_exists( 'meng_blanks_basic', $_POST )) {
			$meng_blanks_basic = $_POST['meng_blanks_basic'];
			$valid_blanks = [];
			$counter = 0;
			foreach( $meng_blanks_basic as $blank ) {
				$counter++;
				$valid_blanks[$counter]['statement'] = $blank['statement'];
				preg_match("/\[\w+\]/i", $blank['statement'], $matches);
				$correct = substr($matches[0], 1, strpos($matches[0], ']')-1);
				$valid_blanks[$counter]['correct'] = trim($correct);
			}
			update_post_meta($post_id, 'meng_blanks_basic', $valid_blanks);
		}

		if(array_key_exists('meng_blanks_cols', $_POST)) {
			$meng_blanks_cols = $_POST['meng_blanks_cols'];
			$valid_cols = [];
			$valid_cols['cols'] = $meng_blanks_cols['cols'];
			foreach($meng_blanks_cols['fields'] as $field_id => $field) {
				$counter = 0;
				$valid_cols['fields'][$field_id]['option_string'] = $field;
				foreach(explode('|', $field) as $f) {
					if(preg_match_all("/\[\w+\]/", trim($f), $matches) > 0) {
						$valid_cols['fields'][$field_id]['options_input'][$counter] = str_replace(['[', ']'], '', $matches[0][0]);
					} else {
						$valid_cols['fields'][$field_id]['options_normal'][$counter] = trim($f);
					}
					$counter++;
					// @todo remove this
					$valid_cols['fields'][$field_id]['option_array'][] = trim($f);
				}
			}
			update_post_meta($post_id, 'meng_blanks_cols', $valid_cols);
		}

		if( array_key_exists('meng_multi_selector', $_POST) ) {
			$meng_multi_selectors = $_POST['meng_multi_selector'];
			$valid_questions = [];
			foreach($meng_multi_selectors as $id => $question) {
				if(empty($meng_multi_selectors[$id]['statement'])) continue;
				$valid_questions[$id]['qid'] = $id;
				$valid_questions[$id]['statement'] = $meng_multi_selectors[$id]['statement'];
				$valid_questions[$id]['options']['string'] = $meng_multi_selectors[$id]['options'];
				$valid_questions[$id]['options']['array'] = array_map( 'trim', explode('|', $meng_multi_selectors[$id]['options']) );
				
				foreach( explode('|', $meng_multi_selectors[$id]['options']) as $option_id => $option ) {
					$option = trim($option);
					if(preg_match_all("/\[\w+\]/", $option, $matches ) > 0) {
						$valid_questions[$id]['options']['correct'][$option_id] = substr($option, 1, strpos($option, ']') - 1 );
					}
				}

			}
			update_post_meta($post_id, 'meng_multi_selector', $valid_questions);
		}
	}
}