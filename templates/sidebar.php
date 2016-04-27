<?php 
if (has_nav_menu('sidebar_navigation1')) :
	wp_nav_menu(array('theme_location' => 'sidebar_navigation1', 'menu_class' => 'nav nav-pills nav-stacked'));
endif;

dynamic_sidebar('sidebar-primary'); ?>
