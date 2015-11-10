<?php while (have_posts()) : the_post(); ?>
<article <?php post_class(); ?>>
  <header>
    <h3 class="entry-title"><?php the_title(); ?></h3>
  </header>
  <div class="entry-summary">
  <div class="row">
  <div class="col-xs-12">
<?php $location = ( $location = get_cfc_field( 'events-details' , 'location' ) ) ?
	$location = ' Where: ' . $location :
	$location = 'TBD'; 
$event_date = ( $event_date = new DateTime( get_cfc_field( 'events-details' , 'event-date' ) ) ) ?
	$event_date = ' When: ' . $event_date->format('F d, Y') :
	$event_date = 'TBD';
$event_time = ( $event_time = get_cfc_field( 'events-details' , 'event-time' ) ) ?
	$event_time = ', ' . $event_time :
	$event_time = '';
?>
<ul class="fa-ul">
<li><i class="fa-li fa fa-map"></i><strong><?php echo $location; ?></strong><br /></li>
<li><i class="fa-li fa fa-calendar"></i><strong><?php echo $event_date . $event_time; ?></strong><br /></li>
</ul>
<?php the_content(); ?></div>
  </div>
  </div>
</article><?php endwhile; ?>
<p class="text-right"><a href="/news-events/talks-symposia/" class="btn btn-warning">View All Events</a></p>
