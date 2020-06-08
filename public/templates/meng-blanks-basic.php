<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get excercise by post id provided as shortcode attribute
$excercise = get_post((int) $atts['id']);

if( !is_null($excercise) && $excercise->post_type === 'meng_blanks_basic' ) {
	$blanks = get_post_meta($excercise->ID, 'meng_blanks_basic', true ); ?>
	<div class="meng_blanks_basic_container">
		<div class="meng_blanks_options">
			<?php
			$correct_options = [];
			foreach( $blanks as $blank ) {
				$correct_options[] = $blank['correct'];
			}
			shuffle($correct_options);
			$correct_options_string = implode(' | ', $correct_options);
			?>
			<p><?php echo $correct_options_string; ?></p>
		</div>
		<form method="post" class="meng_blanks_basic_form" id="meng_blanks_basic_form-<?php echo $excercise->ID ?>">
			<table>
				<tbody>
					<?php 
					$counter = 0;
					foreach($blanks as $blank): 
					$counter++;
					?>
						<tr>
							<td><?php echo $counter ?></td>
							<td>
								<?php
								$_prepared_statement = str_replace(
									"[" . $blank['correct'] . "]", 
									"<input type='text' name='meng_blanks_basic[$counter]' data-id='$counter' class='meng_blanks_basic_input'/>", 
									$blank['statement']);
								?>
								<p><?php echo $_prepared_statement ?></p>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php wp_nonce_field('meng_blanks_basic_nonce','_meng_blanks_basic_nonce'); ?>
			<input type="hidden" name="ex_id" value="<?php echo $excercise->ID ?>" id="ex_id">
			<input type="submit" name="meng_blanks_basic_submit" id="meng_blanks_basic_submit" value="Check">
		</form>
	</div>
	<?php
}