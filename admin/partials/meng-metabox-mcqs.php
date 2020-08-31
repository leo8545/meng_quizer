<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;
$mcqs = get_post_meta($post->ID, 'meng_mcqs', true);
?>
<div class="basic_mcqs_wrapper">
	<p class="description"><?php _e("Write options separated by | & write correct options inside brackets e.g. a | [b] | c | d ") ?></p>
	<div class="mcqs">
		<?php 
		if(is_array($mcqs) && count($mcqs) > 0) {
			$counter = 0;
			foreach( $mcqs as $mcq ) { 
				$counter++;
				$statement = sanitize_text_field( $mcq['statement'] );
				$options = $mcq['options']['string'];
				?>
				<div id="mcq-<?php echo $counter ?>" class="meng_quiz_single_field">
					<div><?php echo $counter ?>.</div>
					<label for=""><?php _e("Statment for the mcqs:") ?></label>
					<input name="meng_mcqs[<?php echo $counter ?>][statement]" value="<?php echo $statement ?>" class="mcqs_statement" placeholder="Enter the mcq's statement" />
					<label for=""><?php _e("Options for the mcqs:") ?></label>
					<input name="meng_mcqs[<?php echo $counter ?>][options]" value="<?php echo $options ?>" class="mcqs_options" placeholder="Enter mcqs options here separated by '|'" />
				</div>
				<?php
			}
		}
		
		?>
	</div>
	<div class="btn meng_add_btn"><span id="meng_mcqs_add_btn">Add MCQ</span></div>
</div>