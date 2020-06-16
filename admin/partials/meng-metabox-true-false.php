<?php

global $post;

$questions = get_post_meta( $post->ID, 'meng_true_false', true );
?>

<div class="meng-mb-wrapper meng-mb-multi-selector">
	<div class="meng-description">
		<p class="description"><?php _e('Write options separated by | and use :correct as suffix for correct options', 'meng') ?></p>
	</div>
	<div class="meng-questions">
		<?php 
		if( @$questions ) :
			$counter = 0;
			foreach( $questions as $question ) :
				$counter++; ?>
				<div class="meng_quiz_single_field">
					<div class="meng_counter"><strong><?php echo $counter ?></strong></div>
					<input type="text" name="meng_true_false[<?php echo $counter ?>][statement]" value="<?php echo $question['statement'] ?>" />
					<label><input type="radio" name="meng_true_false[<?php echo $counter ?>][answer]" value="1" <?php checked('1', $question['answer']) ?> />True</label>
					<label><input type="radio" name="meng_true_false[<?php echo $counter ?>][answer]" value="0" <?php checked('0', $question['answer']) ?> />False</label>
				</div> <?php
			endforeach; 
		endif; ?>
	</div>
	<div class="btn meng_add_btn"><span id="meng_true_false_add_btn"><?php _e('Add Question', 'meng') ?></span></div>
</div>
