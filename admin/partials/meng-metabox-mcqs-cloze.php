<?php 
global $post;
preg_match_all("/\[[a-zA-Z]\]/i", $post->post_content, $matches);
$n_options = @$matches ? count( $matches[0] ) : 0;
$mcqs = get_post_meta($post->ID, 'meng_mcqs_cloze', true);
?>
<div class="cloze_mcqs_wrapper">
	<div class="mcqs-cloze">
		<?php if( $n_options > 0 ) : ?>
			<?php foreach( $matches[0] as $option ): 
				$_option = substr($option, 1, 1); // Letter inside brackets e.g. [A] => A
				?>
			<div class="cloze-mcqs-single meng_quiz_single_field" id="cloze-mcqs-single-<?php echo $_option ?>">
				<div class="meng-counter"><?php echo $option ?></div>
				<label for="meng_mcqs_cloze-options-<?php echo $_option ?>"><?php _e('Options separated by | Add :correct for correct option:') ?></label>
				<input type="text" name="meng_mcqs_cloze[<?php echo $_option ?>][options]" id="meng_mcqs_cloze-options-<?php echo $_option ?>" class="meng-admin_input" value="<?php echo $mcqs[$_option]['options']['string'] ?? "" ?>">
				<label for="<?php echo "meng_mcqs_cloze-desc-$_option"; ?>"><?php _e('Description for the correct option:', 'meng') ?></label>
				<?php wp_editor(
					$mcqs[$_option]['description'] ?? "", 
					"meng_mcqs_cloze-desc-$_option", 
					[
						'textarea_name' => "meng_mcqs_cloze[$_option][description]",
						'media_buttons' => false,
						'drag_drop_upload' => false,
						'textarea_rows' => 4
					]); ?>
			</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</div>
