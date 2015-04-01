<?php 
// Display the CPT
while (have_posts()) : 
	the_post(); 

?>
	<article <?php post_class(); ?>>
<?php
	// get the meta data for this CPT:
	if ( !check_wck() ) return;
	
	//get the user data
	$this_speaker_bio_meta = get_cfc_field( 'biography-meta' , 'userid' , false ) ;
	$this_speaker_id = $this_speaker_bio_meta['ID'] ;
	display_speaker_profile( $this_speaker_id , get_the_ID() );
?>
</article>
<?php 
endwhile; 
?>
