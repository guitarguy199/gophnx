<?php
/**
 * Admin Dashboard layout
 */
?>

<div class="ts-dash">
	<h2 class="nav-tab-wrapper">
	
		<a class="nav-tab <?php echo esc_attr($tab == 'welcome' ? 'nav-tab-active' : ''); ?>" href="<?php 
			echo admin_url('admin.php?page=sphere-dash'); ?>"><?php echo esc_html_x('Welcome', 'Admin', 'contentberg'); ?></a>
		
		<!-- install plugins -->
		<a class="nav-tab" href="<?php echo admin_url('admin.php?page=sphere-dash-demos'); ?>"><?php echo esc_html_x('Import Demos', 'Admin', 'contentberg'); ?></a>
		<a class="nav-tab" href="<?php echo admin_url('admin.php?page=sphere-dash-customize'); ?>"><?php echo esc_html_x('Customize', 'Admin', 'contentberg'); ?></a>
		
		<a class="nav-tab <?php echo esc_attr($tab == 'support' ? 'nav-tab-active' : ''); ?>" href="<?php 
			echo esc_url(admin_url('admin.php?page=sphere-dash-support')); ?>"><?php echo esc_html_x('Help & Support', 'Admin', 'contentberg'); ?></a>
	</h2>
	
	<div>
		<?php include locate_template('inc/admin/views/dash-' . sanitize_file_name($tab) . '.php'); ?>
	</div>
	

</div>