<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$excercise = get_post((int) $atts['id']);
if( !is_null($excercise) && $excercise->post_type === 'meng_sortables_basic' ) {
	$sortables = get_post_meta($excercise->ID, 'meng_sortables', true ); 
	?>
	<div class="meng_sortables_container meng-layout_<?php echo in_array($atts['layout'], ['simple', 'left', 'right']) ? $atts['layout'] : 'simple'; ?>">

		
		<ul class="meng_static_text">
			<?php do_action('meng_sortables_basic_before_static_column') ?>
			<?php foreach($sortables as $field):  ?>
				<li><?php echo $field['static'] ?></li>
			<?php endforeach; ?>
			<?php do_action('meng_sortables_basic_after_static_column') ?>
		</ul>

		<ul id="meng_sortables_<?php echo $atts['id'] ?>" class="meng_sortables">
			<?php do_action('meng_sortables_basic_before_sortables_column') ?>
			<?php 
			$original_sortables = $sortables; 
			shuffle($sortables);
			foreach($sortables as $field): ?>
				<li><?php echo $field['dynamic'] ?></li>
			<?php endforeach; ?>
			<?php do_action('meng_sortables_basic_after_sortables_column') ?>
		</ul>
	</div>
	<div class="meng_answers_wrapper">
		<p class="meng_toggle_sibling">show answers</p>
		<table class="meng_sortables_answers" style="display:none;">
			<?php foreach($original_sortables as $field):  ?>
				<tr>
					<td>
						<span><?php echo $field['static'] ?></span>
					</td>
					<td>
						<span><?php echo $field['dynamic'] ?></span>
					</td>
				</tr>
			<?php endforeach; ?>
			</table>
	</div>
	<?php
}