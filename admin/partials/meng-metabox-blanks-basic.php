<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;
$blanks = get_post_meta($post->ID, 'meng_blanks_basic', true);
echo '<pre>';
print_r($blanks);
echo '</pre>';
?>

<div class="meng_blanks_wrapper">
	<div class="meng-blanks">
		<p class="description"><?php _e('Write correct option inside brackets e.g. He [is] a good boy.') ?></p>
	<?php 
	
	if( @$blanks && count($blanks) > 0 ) {
		$counter = 0;
		foreach( $blanks as $blank ) {
			$counter++;
			?>

			<div class="blanks_basic_wrapper meng_quiz_single_field">
				<div class="meng_counter"><?php echo $counter ?></div>
				<label>Enter the statement:</label>
				<input type="text" name="meng_blanks_basic[<?php echo $counter ?>][statement]" class="meng_blanks_basic_statement" value="<?php echo $blank['statement'] ?>">
			</div>

			<?php
		}

	} 
	
	?>


	</div>
	<div class="btn meng_add_btn"><span id="meng_blanks_add_btn">Add MCQ</span></div>
</div>