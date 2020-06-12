<?php 
add_filter('meng_admin_post_column_meng_count_label',function($label, $column_id) {
	return 'No. of questions';
}, 10, 2);
