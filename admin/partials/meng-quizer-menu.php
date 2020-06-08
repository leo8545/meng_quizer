<?php

if( !defined('ABSPATH') ) {
	exit;
}
//Get the active tab from the $_GET param
  $default_tab = null;
  $tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;
?>
<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<nav class="nav-tab-wrapper">
      <!-- <a href="?page=meng_quizer" class="nav-tab <?php if($tab===null):?>nav-tab-active<?php endif; ?>">General</a> -->
      <a href="?page=meng_quizer&tab=help" class="nav-tab <?php if($tab==='help' || $tab === null):?>nav-tab-active<?php endif; ?>">Help</a>
	</nav>
	<div class="tab-content">
		<?php switch($tab) :
      case 'help':
		default:
			require MENG_QUIZ_DIR . '/admin/partials/meng-quizer-menu-help.php';
        break;
    endswitch; ?>
	</div>
</div>