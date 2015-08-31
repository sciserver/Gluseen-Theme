<?php get_template_part('templates/page', 'header'); ?>
<?php
//always get them in order of title, then sort later using metadata if necessary
$args = array(
	'posts_per_page'   => -1,
	'offset'   => 0,
	'orderby'          => 'title',
	'order'            => 'ASC',
	'post_type'        => 'funding',
	'post_status'      => 'publish',
);
$posts_array = get_posts( $args );
?>
<?php
//How will they be displayed?
global $orderby;
global $divideby;
if ( $orderby = get_query_var( 'orderby' , 'title' ) )
	if ( !in_array( $orderby , array( 'title' , 'sponsor' ) ) ) $orderby = 'title';

?>
<p class="h4 pull-left">
<a href="?orderby=title" class="btn btn-primary">Order Alphabetically</a>
<a href="?orderby=sponsor" class="btn btn-primary" alt="coming soon">Order by Agency</a>
<button class="btn btn-primary disabled">Order by Deadline</button>
</p>
<p class="h4 pull-right">
<button class="btn btn-success expand-all" data-group="funding-group">Expand All</button>
<button class="btn btn-danger collapse-all" data-group="funding-group">Collapse All</button>
</p>
<div class="clearfix">&nbsp;</div>
<div class="funding-group">
<?php
// show the posts, reordeed as necessary
switch ( $orderby ) {
	case 'sponsor' :
		foreach ( $posts_array as $post ) {
			$sponsors[] = get_cfc_field( 'funding-details' , 'sponsor' , $post->ID ) ;
		}
		$all_sponsors = array_unique( $sponsors );
		sort( $all_sponsors );
		foreach ( $all_sponsors as $this_sponsor ) : 
			$first=true;
			foreach ( $posts_array as $post ) :
				if ( strcmp( get_cfc_field('funding-details' , 'sponsor' , $post->ID ) , $this_sponsor ) !== 0 ) continue;
				setup_postdata( $post ); 
				if ($first) echo "<h2>$this_sponsor</h2>";
				$first=false;
				get_template_part('templates/content', 'funding');
			endforeach;
		endforeach;
		break;
	case 'title':
	default:
		foreach ( $posts_array as $post ) : setup_postdata( $post ); 
			get_template_part('templates/content', 'funding');
		endforeach;
		break;
}
?>
</div>