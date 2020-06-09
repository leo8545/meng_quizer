<?php 

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;

$blanks = get_post_meta($post->ID, 'meng_blanks_cols', true);
$count = 0;
if(@$blanks && count($blanks) > 0) {
	$count = (int) $blanks['cols']['count'];
}
echo '<pre>';
print_r($blanks);
echo '</pre>';
?>

<div class="meng_blanks_cols_wrapper">
	<div class="blanks-cols-count">
		<label for="meng-blanks-count-cols">No. of columns:</label>
		<input type="number" required min="2" max="4" name="meng_blanks_cols[cols][count]" id="meng-blanks-count-cols" value="<?php echo $count ? $count : ''; ?>"/>
		<span id="meng-blanks-cols-names-btn">click to generate columns</span>
		<div class="meng-blanks-cols-names">
			<?php for( $i = 1; $i <= $count; $i++ ): ?>
				<div id="meng-blanks-col-<?php echo $i ?>">
					<label>Label for column <?php echo $i ?></label>
					<input type="text" required name="meng_blanks_cols[cols][names][<?php echo $i ?>]" value="<?php echo $blanks['cols']['names'][$i] ?>"/>
				</div>
			<?php endfor; ?>
		</div>
	</div>
	<div class="blanks-cols-fields">
		<?php 
		$counter = 0;
		if( @$blanks['fields'] && count($blanks['fields']) > 0 ):
			foreach($blanks['fields'] as $blank) : 
				$counter++;
		?>
			<div class="blanks_cols_field_wrapper meng_quiz_single_field">
				<div class="meng_counter"><?php echo $counter ?></div>
				<label>Options: <i>separated by '|'</i></label>
				<input type="text" name="meng_blanks_cols[fields][<?php echo $counter ?>]" class="meng_blanks_cols_options" value="<?php echo $blank['option_string'] ?>" />
			</div>
		<?php 
			endforeach; 
		endif;
		?>
	</div>
	<div class="btn meng_add_btn"><span id="meng_blanks_cols_add_btn">Add MCQ</span></div>
</div>