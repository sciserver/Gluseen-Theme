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
echo "<div class='row dd2-targets'>";
foreach ( $all_affiliates as $this_affiliate) {
	$target_class = ' dd-target sch-all ' . implode( ' ' , array_keys( $this_affiliate[ 'schools' ] ) );
	if ( count( $this_affiliate[ 'depts' ] ) ) $target_class .= ' dept-all ' . implode( ' ' , array_keys( $this_affiliate[ 'depts' ] ) );
	echo "<div class='$target_class'>";
	echo "<div class='col-lg-4 col-sm-6 col-xs-12'>";
	echo "<div class='well drilldown-target'>";
	echo "<p class='bigger'><strong>" . $this_affiliate['display_name'] . "</strong></p>";
	echo "<p>";
	foreach( $this_affiliate[ 'depts' ] as $this_dept ) echo '<em>' . $this_dept['display_name'] . '</em><br />';
	echo "</p>";
	echo "<p>";
	foreach( $this_affiliate[ 'schools' ] as $this_school ) echo '<strong>' . $this_school['display_name'] . '</strong><br />';
	echo "</p>";
	echo "</div>";
	echo "</div>";
	echo "</div>";
}
echo "</div>";
?>
</div>
<div class="col-sm-3 hidden-xs">
<?php
// create a well for schools
// show all schools
?>
<div class="form-horizontal dd2-controls">
<div class="panel panel-default">
<div class="panel-heading"><h4>Schools <div class="alignright"><a role="button" data-toggle="collapse" href="#collapseSch" aria-expanded="false" aria-controls="collapseSch"><i class="fa fa-bars fa-3"></i></a></div></h4></div>
<div class="panel-body collapse in" id="collapseSch">
<div class="form-group""><div class="col-xs-2">
<input type='checkbox' checked='checked'  role='toggle' data-toggle='drilldown2' data-target='.sch-all' id="dd2-sch-all">
</div><div class="col-xs-10">
<label for="sch-all" class="bigger">All Schools<span id="sch-count"></span></label>
</div></div>
<?php // loop through all schools, display their name, checkbox (checked) and make them data-toggles for sch-#### 
foreach($all_schools as $this_key=>$this_school) {
	echo '<div class="form-group"><div class="col-xs-2">';
	echo "<input type='checkbox' role='toggle' data-toggle='drilldown2' data-target='.$this_key' id='dd2-.$this_key' > ";
	echo '</div><div class="col-xs-10">';
	echo "<label for='dd2-.$this_key'>" . $this_school['display_name'] . " <span id='$this_key-count'></span></label>";
	echo '</div></div>';
} ?>
</div>
</div>
<?php
// create a well for departments
// show all departments
?>
<div class="panel panel-default">
<div class="panel-heading"><h4>Departments <div class="alignright"><a role="button" data-toggle="collapse" href="#collapseDept" aria-expanded="false" aria-controls="collapseDept"><i class="fa fa-bars fa-3"></i></a></div></h4></div>
<div class="panel-body collapse in" id="collapseDept">
<div class="form-group"><div class="col-xs-2">
<input type='checkbox' checked='checked' role='toggle' data-toggle='drilldown2' data-target='.dept-all' id="dd2-dept-all">
</div><div class="col-xs-10">
<label for="dept-all" class="bigger"> All Departments<span id="dept-count"></span></label><br/>
</div></div>
<?php
// loop through all departments, display_name, checkbox (checked) and make them data-toggles for dept-#### 
foreach($all_departments as $this_key=>$this_department) {
	echo '<div class="form-group"><div class="col-xs-2">';
	echo "<input type='checkbox' role='toggle' data-toggle='drilldown2' data-target='.$this_key' id='dd2-.$this_key' > ";
	echo '</div><div class="col-xs-10">';
	echo "<label for='dd2-.$this_key'>" . $this_department['display_name'] . " <span id='$this_key-count'></span></label><br/>";
	echo '</div></div>';
}
?>
</div>
</div>
</div>
</div>
</div>
