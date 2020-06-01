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
});

add_action('wp_ajax_my_action', function() {
	check_ajax_referer( 'my-special-string', 'security' );
	$serialized = $_POST['serialized'];
	$ex_id = $_POST["exId"];
	$ex = get_post_meta((int) $ex_id,'meng_mcqs',true);
	$result = [];
	foreach($ex as $k => $e) {
		$result[$k] = $e['options']['correct'];
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
		add_meta_box(
			'meng_quiz_mcqs_basic',
			'Mcqs Basic',
			[self::class, 'meng_basic_mcqs_callback'],
			'meng_mcqs_basic'
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
				if(count($mcqs) > 0) {
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
									$answer = true;
								}
								$id = "mcq-$key-option-$opt_key"; ?>
								<label for="<?php echo $id ?>"><input type="radio" name="mcq[<?php echo $key ?>]" id="<?php echo $id ?>" value="<?php echo trim($option) ?>"> <?php echo $option ?></label>
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