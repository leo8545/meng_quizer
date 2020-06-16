<?php  

$questions = get_post_meta((int) $atts['id'], 'meng_true_false', true);

if(@$questions) : ?>
<div class="meng-wrapper meng-true-false-wrapper">

	<form method="post" class="meng-form">
		<div class="meng-questions">
			<ol class="meng-list meng-true-false-list meng-slider">
			<?php foreach( $questions as $qid => $question ) : ?>
				<li class="meng-form-field" id="meng-true-false-<?php echo $qid ?>" data-qid="<?php echo $qid ?>">
					<p><?php _e( $question['statement'], 'meng' ) ?></p>
					<label for="meng_true_false_t-<?php echo $qid ?>">
						<input type="radio" name="meng_true_false[<?php echo $qid ?>]" id="meng_true_false_t-<?php echo $qid ?>" class="meng_true_false_input" value="1">
						<span><?php _e('True', 'meng') ?></span>
					</label>
					<label for="meng_true_false_f-<?php echo $qid ?>">
						<input type="radio" name="meng_true_false[<?php echo $qid ?>]" id="meng_true_false_f-<?php echo $qid ?>" class="meng_true_false_input" value="0">
						<span><?php _e('False', 'meng') ?></span>
					</label>
				</li>
			<?php endforeach; ?>
			</ol>
		</div>
		<?php wp_nonce_field('meng_tf_nonce','_meng_tf_nonce'); ?>
		<input type="hidden" id="ex_id" value="<?php echo (int) $atts['id'] ?>">
		<button id="btn">Check</button>

	</form>

</div>

<?php endif;