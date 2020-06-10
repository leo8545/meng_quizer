<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get excercise by post id provided as shortcode attribute
$excercise = get_post((int) $atts['id']);
?>
<div class="meng-blanks-cols-wrapper">
<?php
if( !is_null($excercise) && $excercise->post_type === 'meng_blanks_cols' ) {
	$blanks = get_post_meta($excercise->ID, 'meng_blanks_cols', true );
	$cols_count = (int) $blanks['cols']['count'];
	$cols_names = $blanks['cols']['names'];
	$fields = $blanks['fields'];

	?>
	<div class="meng-blanks-cols meng-blanks-cols-<?php echo $cols_count ?>">
	<form method="post" class="meng_blanks_cols_form" id="meng_blanks_cols_form_<?php echo $excercise->ID; ?>">
		<table>
			<tr class="meng-blanks-cols-names">
				<?php for($i = 1; $i <= $cols_count; $i++) : ?>
					<td class="meng-blanks-col-name"><?php echo $cols_names[$i] ?></td>
				<?php endfor; ?>
			</tr>
			<?php foreach($fields as $id => $field): ?>
				<tr class="meng-blanks-cols-fields">
					<?php for($i = 0; $i < count($field['option_array']); $i++) : ?>
						<td class="meng-blanks-cols-field">
							<?php
							$val = $field['option_array'][$i];
							$_val = $val;
							if( preg_match("/\[\w+\]/i", $val, $matches) === 1) {
								$_val = str_replace(']', '', explode('[', $matches[0])[1]);
								$_val = "<input type='text' name='meng_blanks_cols[$id][$i]' class='meng_blanks_cols_input' data-field_id='$id' data-option_id='$i' />";
							}
							?>
							<span><?php echo $_val ?></span>
						</td>
					<?php endfor; ?>
				</tr>
			<?php endforeach; ?>
		</table>
		<?php wp_nonce_field('meng_mcqs_nonce','_meng_mcqs_nonce'); ?>
		<input type="hidden" name="ex_id" value="<?php echo $excercise->ID ?>" id="ex_id">
		<input type="submit" name="meng_mcqs_submit" id="meng_mcqs_submit" value="Check">
	</form>
	</div>
	
	<?php
}

?>

</div>
