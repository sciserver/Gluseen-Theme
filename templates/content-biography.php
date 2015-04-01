<?php 
/*
 *
 * Show Speaker Bio if the user is logged in and is a Speaker.
 * Provide link to Speaker-Bio Archive page for Admin and Editors.
 * 
 *
 */
 // Must be logged in
if ( is_user_logged_in() ) {

	// Must be a speaker to view/edit own biography
	$current_user_id = get_idies_login( 'speakers' ) ;
	if ( $current_user_id ) {
	
		// Must have a  page to edit - the admin will create it for the speaker.
		$current_user_bio_id = get_idies_speaker_bio( $current_user_id , 'speaker-bio' );
		if ( $current_user_bio_id ) {
		
			$current_user_action = get_query_var( 'action' );
			$current_user_cfc = get_query_var( 'cfc' );
			
			if ( !empty( $current_user_action ) ) {
				
				$biography_template = 'biography' . '-' . $current_user_action . '-' . $current_user_action ;	
			
				// Create a function to parse the cfc fields and display a form
				get_template_part('templates/content', 'biography' . '-' . $current_user_action . '-' . $current_user_cfc ); 

			} else {
				
				// This is the existing function that only displays the bio, not the profile pic or attachments.
				display_speaker_profile($current_user_id, $current_user_bio_id);
			
			}
			
		} elseif (current_user_can( 'edit_others_posts' ) ) {
			echo "<strong>This user does not yet have a Biography. <a href='wp-admin/edit.php?post_type=speaker-bio'>Add Biography</a>.<br> \n"; 
		}
	} elseif (current_user_can( 'edit_others_posts' ) ) {
			echo "<strong>Administrators</strong> and <strong>Editors</strong> can update Speaker Biographies from the <a href='http://test.idies.jhu.edu/symposium/speaker-bio/'>Speakers Archive page</a>.<br> \n"; 
	}
} else {
	//show link to login
	?>
	<div class="col-xs-6 col-md-10"><strong>Speakers must log in to view and or edit their biography and attachments.</strong></div>
	<?php
}
?>

