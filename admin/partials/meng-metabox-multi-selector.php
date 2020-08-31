<?php

global $post;

$questions = get_post_meta($post->ID, 'meng_multi_selector', true);

?>

<div class="meng-mb-wrapper meng-mb-multi-selector">
	<div class="meng-description">
		<p class="description"><?php _e('Write options separated by | and write correct options inside brackets e.g. a | [b] | [c] | d', 'meng') ?></p>
	</div>
	<div class="meng-questions">
		<?php 
		if( @$questions && count($questions) > 0 ) :
			$counter = 0;
			foreach($questions as $question) : 
			$counter++; ?>
			<div class="meng_quiz_single_field">
				<div class="meng_counter"><strong>Question: <?php echo $counter ?></strong></div>
				<div class="meng-form-field">
					<label for="meng_multi_selector_statement-<?php echo $counter ?>"><?php _e('Statement', 'meng') ?></label>
					<input type="text" name="meng_multi_selector[<?php echo $counter ?>][statement]" id="meng_multi_selector_statement-<?php echo $counter ?>" value="<?php echo $question['statement'] ?>">
				</div>
				<div class="meng-form-field">
					<label for="meng_multi_selector_options-<?php echo $counter ?>"><?php _e('Options', 'meng') ?></label>
					<input type="text" name="meng_multi_selector[<?php echo $counter ?>][options]" id="meng_multi_selector_options-<?php echo $counter ?>" value="<?php echo $question['options']['string'] ?>">
				</div>
			</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
	<div class="btn meng_add_btn"><span id="meng_multi_selector_add_btn"><?php _e('Add Question', 'meng') ?></span></div>
</div>