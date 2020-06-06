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
	}
}