<?php
/* 
 * Show affiliated people on the affiliates page. 
 */ 
?>
<?php while (have_posts()) : the_post(); ?>
<?php the_content(); ?>
<?php endwhile; ?>
<?php
echo "<!-- people -->"; 

// which schools, centers, or departments to show. Default is all.
$orderby = get_query_var( 'orderby' , 'last' );

// get and flatten schools, centers, depts, and affiliates (putting school info in depts and dept/center info in affiliates
$all_affiliates = idies_get_affiliates(  );
$all_departments = idies_get_departments( $all_affiliates );
$all_schools = idies_get_schools( $all_affiliates );

//default is order by last_name
switch ( $orderby ) {
	case 'dept':
		uasort( $all_affiliates , 'idies_sort_by_dept' );
	break;
	case 'school' :
		uasort( $all_affiliates , 'idies_sort_by_school' );
	break;
	case 'last' :
	default:
		uasort( $all_affiliates , 'idies_sort_by_last' );
}
uasort( $all_departments , 'idies_sort_by_display_name' );
uasort( $all_schools , 'idies_sort_by_display_name' );

// affiliates on the left, menu on the right
?>
<div class="row">
<div class="col-sm-9 col-xs-12">
<?php
$count = 1;
echo "<div class='row'>";
foreach ( $all_affiliates as $this_affiliate) {
	$wrapper_class = ' sch-all ' . implode( ' ' , array_keys( $this_affiliate[ 'schools' ] ) );
	if ( count( $this_affiliate[ 'depts' ] ) ) $wrapper_class .= ' dept-all ' . implode( ' ' , array_keys( $this_affiliate[ 'depts' ] ) );
	echo "<div class='$wrapper_class'>";
	echo "<div class='col-lg-4 col-sm-6 col-xs-12'>";
	echo "<div class='well drilldown-target'>";
	echo "<p><strong>" . $this_affiliate['display_name'] . "</strong></p>";
	echo "<p>";
	foreach( $this_affiliate[ 'depts' ] as $this_dept ) echo '<em>' . $this_dept['display_name'] . '</em><br />';
	echo "</p>";
	echo "<p>";
	foreach( $this_affiliate[ 'schools' ] as $this_school ) echo '<strong>' . $this_school['display_name'] . '</strong><br />';
	echo "</p>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
	//if ( !( $count % 3 ) ) echo '<div class="clearfix visible-lg-block"></div>';
	//if ( !( $count++ % 2 ) ) echo '<div class="clearfix visible-md-block visible-sm-block"></div>';
}
echo "</div>";
?>
</div>
<div class="col-sm-3 hidden-xs">
<?php
// create a well for schools
// show all schools
?>
<div class="well">
<ul class="nav">
<li role='toggle' data-toggle='drilldown' data-target='.sch-all' aria-expanded='true' aria-controls='sch-all'><ul>All Schools<span id="sch-count"></span></ul></li>
<?php
// loop through all schools, display their name, checkbox (checked) and make them data-toggles for sch-#### 
foreach($all_schools as $this_key=>$this_school) {
	echo "<li role='toggle' data-toggle='drilldown' data-target='.$this_key' aria-expanded='true' aria-controls='$this_key'>" . $this_school['display_name'] . "<span id='$this_key-count'></span></li>";
}
?>
</ul>
</div>
<?php
// create a well for departments
// show all departments
?>
<div class="well">
<ul class="nav">
<li role='toggle' data-toggle='drilldown' data-target='.dept-all' aria-expanded='true' aria-controls='dept-all'>All Departments<span id="dept-count"></span></li>
<?php
// loop through all departments, display_name, checkbox (checked) and make them data-toggles for dept-#### 
foreach($all_departments as $this_key=>$this_department) {
	echo "<li role='toggle' data-toggle='drilldown' data-target='.$this_key' aria-expanded='true' aria-controls='$this_key'>" . $this_department['display_name'] . "<span id='$this_key-count'></span></li>";
}
?>
</ul>
</div>
</div>
</div>
