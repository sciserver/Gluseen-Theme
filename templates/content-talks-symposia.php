<div class="well">
<article <?php post_class(); ?>>
  <header>
    <h3 class="entry-title"><?php echo get_post_format(); ?><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
  </header>
  <div class="entry-summary">
  <div class="row">
  <div class="col-xs-12">
<?php $location = ( $location = get_cfc_field( 'events-details' , 'short-location' ) ) ?
	$location = ' Where: ' . $location :
	$location = 'TBD'; 
$event_date = ( $event_date = new DateTime( get_cfc_field( 'events-details' , 'event-date' ) ) ) ?
	$event_date = ' When: ' . $event_date->format('F d, Y') :
	$event_date = 'TBD';
$event_time = ( $event_time = get_cfc_field( 'events-details' , 'event-time' ) ) ?
	$event_time = ', ' . $event_time :
	$event_time = '';
	echo '<i class="fa fa-map"></i><strong>' . $location . '</strong><br />';
	echo '<i class="fa fa-calendar"></i><strong>' . $event_date . $event_time . '</strong>';
?>
<?php the_excerpt(); ?></div>
  </div>
  </div>
</article>
</div>
