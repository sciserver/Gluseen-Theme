<?php get_template_part('templates/page', 'header'); ?>
<?php
$args = array(
	'posts_per_page'   => -1,
	'offset'   => 0,
	'orderby'          => 'title',
	'order'            => 'ASC',
	'post_type'        => 'events',
	'post_status'      => 'publish',
);
$posts_array = get_posts( $args );
$upcoming = 0;
$today = new DateTime();
?>
<h2>Upcoming Events</h2>
<?php foreach ( $posts_array as $post ) : 
	setup_postdata( $post ); 	
	//it's ok not to have a date, but if it has a date that is past, skip it an show it under Past Events.
	if ( $event_date = new DateTime( get_cfc_field( 'events-details' , 'event-date' ) ) ) 
		if ($event_date < $today) continue;
	$upcoming++; 	
	get_template_part('templates/content', 'talks-symposia'); 
endforeach; ?>
<?php if (!$upcoming) : ?>
  <div class="alert alert-warning">
    <?php _e('Sorry, no upcoming events found at this time.', 'roots'); ?>
  </div>
<?php 	return; ?>
<?php endif; ?>
<?php $past = 0; ?>
<h2>Past Events</h2>
<?php foreach ( $posts_array as $post ) : 
	setup_postdata( $post ); 	
	//it's ok not to have a date, but if it has a date that is past, skip it an show it under Past Events.
	if ( $event_date = new DateTime( get_cfc_field( 'events-details' , 'event-date' ) ) ) 
		if ($event_date >= $today) continue;
	$past++; 	
	get_template_part('templates/content', 'talks-symposia'); 
endforeach; ?>
<?php if (!$past) : ?>
  <div class="alert alert-warning">
    <?php _e('Sorry, no past events found.', 'roots'); ?>
  </div>
<?php 	return; ?>
<?php endif; ?>

