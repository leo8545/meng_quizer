<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;
$sortables = get_post_meta($post->ID, 'meng_sortables', true);
?>
<div class="basic_sortables_wrapper">
	<p class="description"><?php _e("The values in second input of each field will be draggable.", "meng") ?></p>
	<div class="meng_sortables">
		<?php if(is_array($sortables) && count($sortables) > 0): $counter = 0; ?>
			<?php foreach($sortables as $field): $counter++; ?>
				<div class="sortables_field_wrapper meng_quiz_single_field">
					<div class="meng_counter"><?php echo "$counter. " ?></div>
					<input type="text" name="meng_sortables[<?php echo $counter ?>][static]" class="meng_sortables_static_input" value="<?php echo sanitize_text_field($field['static']) ?>">
					<input type="text" name="meng_sortables[<?php echo $counter ?>][dynamic]" class="meng_sortables_dynamic_input" value="<?php echo sanitize_text_field($field['dynamic']) ?>">
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
	<div class="btn meng_add_btn"><span id="meng_sortable_add_btn">Add Sortable</span></div>
</div>