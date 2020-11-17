<?php 

//Admin page html callback
//Print out html for admin page
function admin_page_html() {
  // check user capabilities
  if ( ! current_user_can( 'manage_options' ) ) {
	return;
  }

  //Get the active tab from the $_GET param
  $default_tab = null;
  $tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;

  ?>
  <!-- Our admin page content should all be inside .wrap -->
  <div class="wrap">
	<!-- Print the page title -->
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<!-- Here are our tabs -->
	<nav class="nav-tab-wrapper">
	  <a href="?page=my-plugin" class="nav-tab <?php if($tab===null):?>nav-tab-active<?php endif; ?>>Default Tab</a>
	  <a href="?page=my-plugin&tab=settings" class="nav-tab <?php if($tab==='settings'):?>nav-tab-active<?php endif; ?>>Settings</a>
	  <a href="?page=my-plugin&tab=tools" class="nav-tab <?php if($tab==='tools'):?>nav-tab-active<?php endif; ?>>Tools</a>
	</nav>

	<div class="tab-content">
	<?php switch($tab) :
	  case 'settings':
		echo 'Settings'; //Put your HTML here
		break;
	  case 'tools':
		echo 'Tools';
		break;
	  default:
		echo 'Default tab';
		break;
	endswitch; ?>
	</div>
  </div>
  <?php
}