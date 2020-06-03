<?php
/**
 * Plugin Name: MENG Quizer
 * Author: Sharjeel Ahmad
 */

define('MENG_QUIZ_URI', plugin_dir_url(__FILE__));

add_action('admin_enqueue_scripts', function() {
	wp_enqueue_style("meng_quiz_style_admin", MENG_QUIZ_URI . "/admin/assets/css/meng_admin_style.min.css");
	wp_enqueue_script('meng_quiz_script', MENG_QUIZ_URI . '/admin/assets/js/main.js', ['jquery']);
});

add_action('wp_enqueue_scripts', function() {
	wp_enqueue_style("meng_quiz_style_front", MENG_QUIZ_URI . "/assets/css/meng_quiz_style.min.css");
	wp_enqueue_script('meng_quiz_script_front', MENG_QUIZ_URI . '/assets/js/main.js', ['jquery']);
	wp_localize_script('meng_quiz_script_front', 'ajaxObject', [
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'security' => wp_create_nonce( 'my-special-string' )
		]);
	wp_enqueue_script('meng_quiz_script_jquery_ui', MENG_QUIZ_URI . '/assets/js/jquery-ui.min.js', ['jquery']);
});

add_action('wp_ajax_my_action', function() {
	check_ajax_referer( 'my-special-string', 'security' );
	$serialized = $_POST['serialized'];
	$ex_id = $_POST["exId"];
	$ex = get_post_meta((int) $ex_id,'meng_mcqs',true);
	$result = [];
	foreach($ex as $k => $e) {
		$result[$k] = $e['options']['correct']; // All mcqs with their number => 'answer
	}
	// foreach( explode("&", $serialized) as $chunk ) {
	// 	$params = explode('=', $chunk);
	// 	if($params) {
	// 		if(preg_match("/mcq\[\w+\]/", urldecode($params[0]), $matches)) {
	// 			$mcq_number = (int) explode("[", $matches[0])[1];
	// 			$result[$mcq_number] = $ex[$mcq_number]['options']['correct'];
	// 			// $mcq_user_answer = urldecode($params[1]);
	// 			// if( $ex[$mcq_number]['options']['correct'] == $mcq_user_answer ) {
	// 			// 	$result[] = $mcq_number;
	// 			// }
	// 		}
	// 	}
	// }
	echo json_encode($result);
	die(); // this is required to return a proper result
});


// Create quiz type
add_action( 'init', 'meng_register_quiz_type' );

function meng_register_quiz_type() {
	$args = [
		'labels' => [
			'name' => 'MCQs Basic'
		],
		'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'basic_mcqs' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
	];
	register_post_type('meng_mcqs_basic', $args);

	$args = [
		'labels' => [
			'name' => 'Sortables Basic'
		],
		'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'basic_sortables' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
	];
	register_post_type('meng_sortables_basic', $args);
}

class Meng_Mcqs_Basic_Metabox
{
	public function __construct()
	{
		add_action('add_meta_boxes', [self::class, 'add_meta_box']);
		add_action('save_post', [self::class, 'save']);
	}

	public static function add_meta_box()
	{
		// Basic mcqs
		add_meta_box(
			'meng_quiz_mcqs_basic',
			'Mcqs Basic',
			[self::class, 'meng_basic_mcqs_callback'],
			'meng_mcqs_basic'
		);

		add_meta_box(
			'meng_quiz_mcqs_basic_helper',
			'Shortcode',
			[self::class, 'metabox_helper_callback'],
			'meng_mcqs_basic',
			'side',
			'high'
		);

		// Basic sortables
		add_meta_box(
			'meng_quiz_sortables_basic',
			'Sortables Basic',
			[self::class, 'meng_basic_sortables_callback'],
			'meng_sortables_basic'
		);
		add_meta_box(
			'meng_quiz_sortables_basic_helper',
			'Shortcode',
			[self::class, 'metabox_sortables_helper_callback'],
			'meng_sortables_basic',
			'side',
			'high'
		);
	}

	public function meng_basic_mcqs_callback($post)
	{
		$mcqs = get_post_meta($post->ID, 'meng_mcqs', true);
		echo '<pre>';
		print_r($mcqs);
		echo '</pre>';
		?>
		<div class="basic_mcqs_wrapper">
			<div class="mcqs">
				<?php 
				if(is_array($mcqs) && count($mcqs) > 0) {
					$counter = 0;
					foreach( $mcqs as $mcq ) { 
						$counter++;
						$statement = sanitize_text_field( $mcq['statement'] );
						$options = $mcq['options']['string'];
						?>
						<div id="mcq-<?php echo $counter ?>">
							<div><?php echo $counter ?>.</div>
							<input name="meng_mcqs[<?php echo $counter ?>][statement]" value="<?php echo $statement ?>" class="mcqs_statement" placeholder="Enter the mcq's statement" />
							<input name="meng_mcqs[<?php echo $counter ?>][options]" value="<?php echo $options ?>" class="mcqs_options" placeholder="Enter mcqs options here separated by '|'" />
						</div>
						<?php
					}
				}
				
				?>
			</div>
			<div class="btn add_btn"><span id="meng_mcqs_add_btn">Add MCQ</span></div>
		</div>
		<?php
	}

	public function meng_basic_sortables_callback($post)
	{
		$sortables = get_post_meta($post->ID, 'meng_sortables', true);
		echo '<pre>';
		print_r($sortables);
		echo '</pre>';
		?>
		<div class="basic_sortables_wrapper">
			<div class="meng_sortables">
				<?php if(is_array($sortables) && count($sortables) > 0): $counter = 0; ?>
					<?php foreach($sortables as $field): $counter++; ?>
						<div class="sortables_field_wrapper">
							<div><?php echo "$counter. " ?></div>
							<input type="text" name="meng_sortables[<?php echo $counter ?>][static]" value="<?php echo sanitize_text_field($field['static']) ?>">
							<input type="text" name="meng_sortables[<?php echo $counter ?>][dynamic]" value="<?php echo sanitize_text_field($field['dynamic']) ?>">
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
			<div class="btn add_btn"><span id="meng_sortable_add_btn">Add Sortable</span></div>
		</div>
		<?php	
	}

	public function metabox_helper_callback($post)
	{
		?>
		<div class="meng_shortcode"><strong>[meng_mcqs_basic id="<?php echo $post->ID ?>" layout="simple"]</strong></div>
		<?php
	}

	public static function metabox_sortables_helper_callback($post)
	{
		?>
		<div class="meng_shortcode"><strong>[meng_sortables_basic id="<?php echo $post->ID ?>" layout="simple"]</strong></div>
		<?php	
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
new Meng_Mcqs_Basic_Metabox;

add_shortcode('meng_mcqs_basic', function($atts) {
	$atts = shortcode_atts([
		'id' => 0,
		'layout' => 'simple'
	], $atts, 'meng_mcqs_basic');
	$excercise = get_post((int) $atts['id']);
	ob_start();
	if( !is_null($excercise) && $excercise->post_type === 'meng_mcqs_basic' ) {
		$mcqs = get_post_meta($excercise->ID, 'meng_mcqs', true ); ?>
		<div class="meng_mcqs_container">
			<?php if($atts['layout'] === 'infography') : ?>
				<div class="post_content"><?php echo $excercise->post_content ?></div>
			<?php endif; ?>

			<form method="post" id="mcqs_form">
	
				<?php
				foreach( $mcqs as $key => $mcq ) { 
					// $key++; // to make it start from 1
					$options = $mcq['options']['array']; ?>
					
					<div class="mcq-<?php echo $key ?>">
						<p class="mcq-statement"><?php echo $key ?>. <?php echo htmlspecialchars_decode( $mcq['statement'] ) ?></p>
						<div class="mcq-options">
							<?php foreach($options as $opt_key => $option) :
								$opt_key++; // to make it start from 1
								if( strpos($option,":correct") !== false ) {
									$option = explode(":", $option)[0];
								}
								$id = "mcq-$key-option-$opt_key"; ?>
								<label for="<?php echo $id ?>" class="meng_radio"><input type="radio" name="mcq[<?php echo $key ?>]" id="<?php echo $id ?>" class="meng_mcq_input_radio hidden" value="<?php echo trim($option) ?>"><span class="meng_label"></span><span><?php echo $option ?></span></label>
							<?php endforeach; ?>
						</div>
					</div>
					<?php
				}
				wp_nonce_field('meng_mcqs_nonce','_meng_mcqs_nonce'); ?>
				<input type="hidden" name="ex_id" value="<?php echo $excercise->ID ?>" id="ex_id">
				<input type="submit" name="meng_mcqs_submit" id="meng_mcqs_submit" value="Check">
			</form>
			<div id="meng_mcqs_result"></div>
			<?php if($atts['layout'] === 'infography') : ?>
				<div class="inforgraphy_pic"><img src="<?php echo get_the_post_thumbnail_url($excercise->ID, "full") ?>" alt=""></div>
			<?php endif; ?>
		</div>
		
		<?php
	}
	$output = ob_get_clean();
	return $output;
});

// sortables
add_shortcode("meng_sortables_basic", function($atts) {
	$atts = shortcode_atts([
		'id' => 0,
		'layout' => 'simple'
	], $atts, 'meng_sortables_basic');
	$excercise = get_post((int) $atts['id']);
	ob_start();
	if( !is_null($excercise) && $excercise->post_type === 'meng_sortables_basic' ) {
		$sortables = get_post_meta($excercise->ID, 'meng_sortables', true ); 
		?>
		<div class="meng_sortables_container">
			<ul class="meng_static_text">
				<?php foreach($sortables as $field):  ?>
					<li><?php echo $field['static'] ?></li>
				<?php endforeach; ?>
			</ul>
			<ul id="meng_sortables_<?php echo $atts['id'] ?>" class="meng_sortables">
				<?php 
				$original_sortables = $sortables; 
				shuffle($sortables);
				foreach($sortables as $field): ?>
					<li><?php echo $field['dynamic'] ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<div class="meng_answers_wrapper">
			<p class="meng_toggle_sibling">show answers</p>
			<table class="meng_sortables_answers" style="display:none;">
				<?php foreach($original_sortables as $field):  ?>
					<tr>
						<td>
							<span><?php echo $field['static'] ?></span>
						</td>
						<td>
							<span><?php echo $field['dynamic'] ?></span>
						</td>
					</tr>
				<?php endforeach; ?>
				</table>
		</div>
		<?php
	}
	$output = ob_get_clean();
	return $output;
});