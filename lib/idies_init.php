<?php
/**
 * IDIES initial setup and constants
 */
add_action('after_setup_theme', 'idies_setup');
function idies_setup() {

	// Add more menus
	register_nav_menus(array(
		'secondary_navigation' => __('Secondary Navigation', 'roots')
		));

}

/*
 * Add IDIES Roots Theme Option Page.
*/
add_action('admin_menu', 'idies_theme_menu');

function idies_theme_menu() {

	add_theme_page('IDIES Theme Options', 'IDIES Theme', 'edit_theme_options', 'idies-theme-page', 'idies_theme_options');
	function idies_theme_options() {

		//create custom top-level menu
		?>
		<div class="wrap">
		<h1>IDIES Theme Options</h1>
		<h1>Utilities</h1>
		<table class="form-table">
		<tr valign="top">
		<th scope="row">Disable Pingbacks on Existing Pages</th>
		<td><button class="button-primary disabled" onclick="location.href='#'">Disable Pingbacks</button></td>
		</tr>
		<tr valign="top">
		<th scope="row">Disable Trackbacks on Existing Pages</th>
		<td><button class="button-primary disabled" onclick="location.href='#'">Disable Trackbacks</button></td>
		</tr>
		<tr valign="top">
		<th scope="row">Disable Comments on Existing Pages</th>
		<td><button class="button-primary disabled" onclick="location.href='#'">Disable Comments</button></td>
		</tr>
		</table>
		</div>
		<?php

	}
}

/*
 * Add content to Admin All Pages view.
 * Remove all pingbacks, trackbacks [, and comments] 
 * from existing pages.
*/
add_action( 'admin_notices', 'idies_admin_notices' );
function idies_admin_notices() {
    $currentScreen = get_current_screen();
    if( $currentScreen->id === "edit-page" ) {
		?>
		<?php
	}
}

/**
 * Add more sidebars
 */
function idies_widgets_init(  ) {

	global $IDIES_Web;
	
	for ($indx=1;$indx <= $IDIES_Web->splash_widgets; $indx++) {
		register_sidebar(array(
		'name'          => __('Splash'.$indx, 'roots'),
		'id'            => 'sidebar-splash-'.$indx,
		'before_widget' => '<section class="widget %1$s %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>',
		));
	}

	for ($jndx=1;$jndx <= $IDIES_Web->footer_widgets; $jndx++) {
		register_sidebar(array(
		'name'          => __('Footer'.$jndx, 'roots'),
		'id'            => 'sidebar-footer-'.$jndx,
		'before_widget' => '<section class="widget %1$s %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2>',
		'after_title'   => '</h2>',
		));
	}
	
	register_sidebar(array(
		'name'          => __('Splash Slideshow'),
		'id'            => 'sidebar-slideshow',
		'before_widget' => '<section class="widget %1$s %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<p class="brand-heading">',
		'after_title'   => '</p>',
		));

  
}
add_action('widgets_init', 'idies_widgets_init' );

/**
 * Add extra query variables
 */
function idies_add_query_vars_filter( $vars ){
  $vars[] = "action";
  $vars[] = "cfc";
  return $vars;
}
add_filter( 'query_vars', 'idies_add_query_vars_filter' );
