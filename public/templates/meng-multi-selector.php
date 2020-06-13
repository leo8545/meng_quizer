<?php  
// global $post;
$excercise = get_post_meta((int) $atts['id'], 'meng_multi_selector', true);
if( @$excercise ) : ?>

<div class="meng-wrapper meng-multi-selector-wrapper">

	<form method="post" class="meng-form">
		<ol class="meng-list meng-msel-list">
			<?php foreach($excercise as $ex) : ?>
				<li class="meng-field-group" id="meng-msel-<?php echo $ex["qid"] ?>">
					<label for=""><?php echo $ex['statement']; ?></label>
					<div class="meng-options">
						<?php foreach($ex['options']['array'] as $option_index => $option) : ?>
							<label for="meng_multi_selector_option-<?php echo $ex['qid'] . '-' . $option_index ?>"><input type="checkbox" name="meng_multi_selector[<?php echo $ex['qid'] ?>][<?php echo $option_index ?>]" data-qid="<?php echo $ex['qid'] ?>" data-option_id="<?php echo $option_index ?>" id="meng_multi_selector_option-<?php echo $ex['qid'] . '-' . $option_index ?>" value="<?php echo $option ?>"><?php echo $option ?></label>
						<?php endforeach; ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>
		<?php wp_nonce_field('meng_msel_nonce','_meng_msel_nonce'); ?>
		<input type="hidden" id="ex_id" value="<?php echo (int) $atts['id'] ?>">
		<button id="btn">Check</button>
	</form>

</div>

<?php
endif;
