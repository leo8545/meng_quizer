<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get excercise by post id provided as shortcode attribute
$excercise = get_post((int) $atts['id']);

if( !is_null($excercise) && $excercise->post_type === 'meng_mcqs_basic' ) {
	$mcqs = get_post_meta($excercise->ID, 'meng_mcqs', true ); ?>
	<div class="meng-mcqs-basic-wrapper">
		<?php if($atts['layout'] === 'infography') : ?>
			<div class="post_content"><?php echo $excercise->post_content ?></div>
		<?php endif; ?>

		<?php do_action('meng_mcqs_basic_before_form') ?>

		<form method="post" class="meng-form mcqs-form">

			<?php
			foreach( $mcqs as $key => $mcq ) { 
				// $key++; // to make it start from 1
				$options = $mcq['options']['array']; ?>

				<?php do_action('meng_mcqs_basic_before_single_mcq') ?>
				
				<div class="mcq-<?php echo $key ?> meng-mcq-single" data-qid="<?php echo $key ?>">
					<p class="mcq-statement"><?php echo $key ?>. <?php echo htmlspecialchars_decode( $mcq['statement'] ) ?></p>
					<div class="mcq-options">
						<?php foreach($options as $opt_key => $option) :
							$opt_key++; // to make it start from 1
							if( strpos($option,"[") !== false ) {
								$option = trim( substr($option, strpos($option, "[")+1, strlen(trim($option))-2 ) );
							}
							$id = "mcq-$key-option-$opt_key"; ?>
							<label for="<?php echo $id ?>" class="meng_radio"><input type="radio" name="mcq[<?php echo $key ?>]" id="<?php echo $id ?>" class="meng_mcq_input_radio hidden" value="<?php echo trim($option) ?>"><span class="meng_label"></span><span class="meng-mcq-option-name"><?php echo $option ?></span></label>
						<?php endforeach; ?>
					</div>
				</div>
				<?php
				
				do_action('meng_mcqs_basic_before_single_mcq');

			}
			wp_nonce_field('meng_mcqs_nonce','_meng_mcqs_nonce'); ?>
			<input type="hidden" name="ex_id" value="<?php echo $excercise->ID ?>" id="ex_id">
			<input type="submit" name="meng_mcqs_submit" id="meng_mcqs_submit" value="Check">
		</form>

		<?php do_action('meng_mcqs_basic_after_form') ?>

		<div id="meng_mcqs_result"></div>
		<?php if($atts['layout'] === 'infography') : ?>
			<div class="inforgraphy_pic"><img src="<?php echo get_the_post_thumbnail_url($excercise->ID, "full") ?>" alt=""></div>
		<?php endif; ?>
	</div>
	
	<?php
}