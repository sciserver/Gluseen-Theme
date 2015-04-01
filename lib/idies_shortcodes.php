<?php 
/**
/* Add shortcodes 
 *	[SDSS_TOC selectors="h2, h3"] Shows a table of contents containing all h2 and h3 selectors
 *	[SDSS_TOTOP] Shows an up arrow and "Back to top" that takes you to the top of the current page.
 */
add_shortcode('IDIES_SPEAKER','idies_speaker');
/**
 * 
 **/
function idies_speaker(  ){
	if ( is_user_logged_in() ) {
		get_currentuserinfo();
		return $user_login;
	} else {
		return '';
	}
}
