<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post = get_post((int) $atts['id']);

if( !is_null($post) && $post->post_type === 'meng_mcqs_cloze' ) {
	$cloze = get_post_meta($post->ID, 'meng_mcqs_cloze', true);
	$options = array_keys($cloze);
	?>

	<div class="meng-mcqs-cloze-wrapper" data-excercise="<?php echo $post->ID ?>">

		<?php do_action('meng_cloze_before_content') ?>

		<div class="meng_cloze_content"><?php echo $post->post_content ?></div>

		<?php do_action('meng_cloze_after_content') ?>

		<div class="meng_cloze_options">
			<table>
				<tbody>
					<?php foreach( $cloze as $_option => $meta ): ?>

						<?php do_action('meng_cloze_before_single_row') ?>

						<tr class="meng-cloze-row" id="meng-cloze-row-<?php echo $_option ?>" data-option="<?php echo $_option ?>">
							<?php foreach( $meta['options']['array'] as $opt ): if(strpos($opt, ':correct') !== false) $opt = explode(':correct', $opt)[0]; ?>
								<td><?php echo $opt ?></td>
							<?php endforeach; ?>
						</tr>

						<?php do_action('meng_cloze_after_single_row') ?>

					<?php endforeach; ?>
				</tbody>
			</table>
		</div>

		<?php do_action('meng_cloze_before_answer_wrapper') ?>

		<div class="meng-answers-wrapper">
			<p class="meng-show-answers-btn">show answers</p>
			<div class="meng_cloze_options_desc meng_tabs_wrapper" style="display: none;">
				<div class="meng_tabs_headings">
					<?php foreach( $options as $option ): ?>
						<div class="meng-tab-heading" data-option="<?php echo $option ?>"><?php echo $option ?></div>
					<?php endforeach; ?>
				</div>
				<?php foreach( $cloze as $_option => $meta ): ?>
					<div class="meng_cloze_tab_content" data-option="<?php echo $_option ?>"><?php echo $meta['description']; ?></div>
				<?php endforeach; ?>
			</div>
		</div>

		<?php do_action('meng_cloze_after_answer_wrapper') ?>

	</div>
	<?php
}
?>