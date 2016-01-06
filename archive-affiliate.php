<?php get_template_part('templates/page', 'header'); ?>
<?php if ($wp_query->max_num_pages > 1) : ?>
  <nav class="post-nav">
    <ul class="pager">
      <li class="previous"><?php previous_posts_link(__('&larr; More Affiliates', 'roots')); ?></li>
      <li class="next"><?php next_posts_link(__('More Affiliates &rarr;', 'roots')); ?></li>
    </ul>
  </nav>
<?php endif; ?>
<?php 
$count=0;
while (have_posts()) : the_post(); 
	$count++;
	//start a new row every first item, AND  a new col AND row every first and third item
	if ( $count%4 == 1 ) echo "<div class='row'>";
	if ( ( $count%4 == 1 ) || ( $count%4 == 3 ) ) echo "<div class='col-md-6 col-xs-12'><div class='row'>";
	echo "<div class='col-sm-6 col-xs-12'>";
	get_template_part('templates/content', get_post_type()); 
	echo "</div>";
	//end row and col every second and fourth item AND end row every fourth item
	if ( ( $count%4 == 2 ) || ( $count%4 == 0 ) ) echo "</div></div>";
	if ( $count%4 == 0 ) echo "</div>";
endwhile; 
if ( ( $count%4 == 1 ) || ( $count%4 == 3 ) ) echo "</div></div></div>"; //end row, col, row
if ( $count%4 == 2 ) echo "</div>"; //end col, row
?>

<?php if ($wp_query->max_num_pages > 1) : ?>
  <nav class="post-nav">
    <ul class="pager">
      <li class="previous"><?php previous_posts_link(__('&larr; More Affiliates', 'roots')); ?></li>
      <li class="next"><?php next_posts_link(__('More Affiliates &rarr;', 'roots')); ?></li>
    </ul>
  </nav>
<?php endif; ?>
