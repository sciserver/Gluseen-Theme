<?php get_template_part('templates/page', 'header'); ?>
<?php
//always get them in order of title, then sort later using metadata if necessary
$affiliate_args = array(
	'posts_per_page'   => -1,
	'offset'   => 0,
	'orderby'          => 'title',
	'order'            => 'ASC',
	'post_type'        => 'affiliate',
	'post_status'      => 'publish',
);
$department_args = array(
	'posts_per_page'   => -1,
	'post_type'        => 'department',
	'post_status'      => 'publish',
);
$center_args = array(
	'posts_per_page'   => -1,
	'post_type'        => 'center',
	'post_status'      => 'publish',
);
?>
<?php 
if 	(	(!$affiliates_array = get_posts( $affiliate_args ) ) ||
 	(	!$department_array = get_posts( $department_args ) ) ||
 	(	!$center_array = get_posts( $center_args ) ) ) : ?>
  <div class="alert alert-warning">
    <?php _e('Sorry, an unexpected error has occured. Please contact the idies-webmaster@lists.jhu.edu.', 'roots'); ?>
  </div>
<?php 	return; ?>
<?php endif; ?>

<?php
echo 'Department : ' . get_query_var( 'dept' , 'All' ) . '<br />';
echo 'Center : ' . get_query_var( 'cent' , 'All' ) . '<br />';
echo 'School : ' . get_query_var( 'sch' , 'All' ) . '<br />';
?>
<?php
// show the posts, reordeed as necessary
foreach ( $affiliates_array as $post ) : setup_postdata( $post ); 
	//get_template_part('templates/content', 'affiliate');
endforeach;
?>
</div>