<?php
/* 
 * Show affiliated people on the affiliates page. 
 */ 
?>
<?php 
while (have_posts()) : the_post(); 
	the_content(); 
endwhile; 

// Find page to show from query var & check it
$tab_pane = get_query_var( 'idies-affil-pane' , 'people' );
if ( !in_array( $tab_pane , array( 'people', 'execcomm' , 'staff' ) ) ) $tab_pane = 'people';

$i=1;

// get and flatten schools, centers, depts, and affiliates (putting school info in depts and dept/center info in affiliates
$all_affiliates = idies_get_affiliates( "last" );
$all_affiliates = get_affiliate_wells( $all_affiliates );

$all_departments = idies_get_departments( $all_affiliates );
$all_schools = idies_get_schools( $all_affiliates );

$execcomm_affiliates = idies_filter_affil( $all_affiliates , "execcomm" , TRUE);
$people_affiliates = idies_filter_affil( $all_affiliates , "staff" , FALSE );
$staff_affiliates = idies_filter_affil( $all_affiliates , "staff" , TRUE );

?>
<div>
  <!-- Nav tabs -->
  <ul id="affiliateTabs" class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#people" aria-controls="people" role="tab" data-toggle="tab" class="h4">Affiliates</a></li>
    <li role="presentation"><a href="#execcomm" aria-controls="execcomm" role="tab" data-toggle="tab" class="h4">Executive Committee</a></li>
    <li role="presentation"><a href="#staff" aria-controls="staff" role="tab" data-toggle="tab" class="h4">Staff</a></li>
  </ul>

  <!-- Tab panes -->
	<div class="tab-content">
  
		<!-- People pane -->
		<?php $context='people';?>
		<div role="tabpanel" class="tab-pane active filterz" id="<?php echo $context; ?>">
			<div class="row">
<?php			// Show affiliates ?>
				<div class="col-sm-9 col-xs-12">
					<div class="sortablz" data-sortablz-safemode>
					<div class="filterz-filters"></div>
<?php
						$orderby_options = array( 'last' => 'Last Name', 'dept' => 'Department'  , 'school' => 'School' );
						show_orderby( $orderby_options , $i++ ) ;
?>
						<div class='row filterz-targets'>
							<div class="filterz-noresults text-center"></div>
<?php
							foreach ( $people_affiliates as $this_affiliate) {
								echo $this_affiliate['well'];
							}
?>
						</div>
					</div>
				</div>

<?php			// Show sidebar controls ?>
				<div class="col-sm-3 hidden-xs">
					<div class="filterz-overview">Showing <span class="showing"></span> of <span class="total"></span> Affiliates</div>
				</div>
				<div class="col-sm-3 hidden-xs">
					<div class="form-inline filterz-controls">
						<div class="panel panel-default">
							<div class="panel-heading"><h4>Schools <div class="alignright"><a role="button" data-toggle="collapse" href="#<?php echo $context; ?>-collapseSch" aria-expanded="false" aria-controls="collapseSch"><i class="fa fa-bars fa-3"></i></a></div></h4></div>
								<div class="panel-body collapse in " id="<?php echo $context; ?>-collapseSch">
<?php 
									// toggles for schools
									foreach($all_schools as $this_key=>$this_school) {
										echo '<div class="form-group">';
										echo "<label><input type='checkbox' role='toggle' data-toggle='filterz' data-group='sch' data-target='$this_key'> <span class='name'>";
										echo $this_school['display_name'] . " </span><span class='count'></span></label>";
										echo '</div><br>';
									} 
?>
								</div>
							</div>
<?php
							// create a well for departments
							// show all departments
?>
							<div class="panel panel-default">
							<div class="panel-heading"><h4>Departments <div class="alignright"><a role="button" data-toggle="collapse" href="#<?php echo $context; ?>-collapseDept" aria-expanded="false" aria-controls="collapseDept"><i class="fa fa-bars fa-3"></i></a></div></h4></div>
								<div class="panel-body collapse in" id="<?php echo $context; ?>-collapseDept">
<?php
									// toggles for departments
								foreach($all_departments as $this_key=>$this_department) {
									echo '<div class="form-group">';
									echo "<label><input type='checkbox' role='toggle' data-toggle='filterz'  data-group='dept' data-target='$this_key'> <span class='name'>";
									echo $this_department['display_name'] . " </span><span class='count'></span></label>";
									echo '</div><br>';
								}
?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<!-- Executive Committee pane -->
		<?php $context='execcomm';?>
		<div role="tabpanel" class="tab-pane <?php if ($tab_pane == 'execcomm') echo "active"; ?>" id="<?php echo $context; ?>">
			<div class="row">
				<div class="col-xs-12">
					<div class="sortablz">
<?php
					$orderby_options = array( 'last' => 'Last Name' , 'title' => 'Title');
					show_orderby( $orderby_options , $i++ ) ;

?>
					<div data-toggle="orderby" data-orderby-targets="last,title" data-orderby-titles="'Last Name','Title'">
						<div class='row '>
<?php
						foreach ( $execcomm_affiliates as $this_affiliate) {
							echo $this_affiliate['well'];
						}
?>
						</div>
					</div>
				</div>
				</div>
			</div>
		</div>
		
		<!-- Staff pane -->
		<?php $context='staff';?>
		<div role="tabpanel" class="tab-pane <?php if ($tab_pane == 'staff') echo "active"; ?>" id="<?php echo $context; ?>">
			<div class="row">
				<div class="col-xs-12">
					<div class="sortablz">
<?php
					$orderby_options = array( 'last' => 'Last Name' , 'title' => 'Title');
					show_orderby( $orderby_options , $i++ ) ;
?>				
					<div id="orderby-group-staff" data-toggle="orderby" data-orderby-targets="last,title" data-orderby-titles="'Last Name','Title'">
<?php
					foreach ( $staff_affiliates as $this_affiliate) {
						echo $this_affiliate['well'];
					}
?>
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php //AFFILIATE FUNCTIONS 

// Show an affiliate in a formatted well.
function show_affiliate_well( $this_affiliate , $affil_class = "" , $attributes = "") {
	echo "<div class='$affil_class' $attributes>";
	echo "<div class='col-lg-4 col-sm-6 col-xs-12'>";
	echo "<div class='well'>";
	echo "<p class='bigger'><strong><a href='" . home_url() . "/affiliates/" . $this_affiliate['post_title'] . "'>" . $this_affiliate['display_name'] . "</a></strong></p>";
	if ( !empty( $this_affiliate['idies_title'] ) ) echo "<p>" . $this_affiliate['idies_title'] . "</strong></p>";
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

// Show the Order By options
function show_orderby( $options = array() , $i ) {

	echo "<div class='form-horizontal orderby_options text-center panel panel-info'>";
	echo "<div class='panel-heading'>";
	echo "<div class='row '>";
	echo "<div class='col-sm-3 col-xs-12'><strong>Order by: </strong></div>";
	
	$checked = 'checked=true';
	foreach ( $options as $this_option_value => $this_option_name ) {		
		echo "<div class='col-sm-3 col-xs-12'>";
		echo "<label><input type='radio' name='sortablz$i' data-toggle='sortablz' data-sortablz='$this_option_value' $checked >$this_option_name</label>";
		echo "</div>";
		$checked = '';
	}
	
	echo "</div>";
	echo "</div>";
	echo "</div>";
	
	return;
}

// Get an affiliate in a formatted well.
function get_affiliate_well( $this_affiliate , $affil_class = "" , $attributes = "") {
	$result =  "<div class='sortablz-target' >";
	$result .=  "<div class='$affil_class sortablz-contents' $attributes>";
	$result .= "<div class='col-lg-4 col-sm-6 col-xs-12'>";
	$result .= "<div class='well'>";
	$result .= "<p class='bigger'><strong><a href='" . home_url() . "/affiliates/" . $this_affiliate['post_title'] . "'>" . $this_affiliate['display_name'] . "</a></strong></p>";
	if ( !empty( $this_affiliate['idies_title'] ) ) $result .= "<p>" . $this_affiliate['idies_title'] . "</strong></p>";
	$result .= "<p>";
	foreach( $this_affiliate[ 'depts' ] as $this_dept ) $result .= '<em>' . $this_dept['display_name'] . '</em><br />';
	$result .= "</p>\n<p>";
	foreach( $this_affiliate[ 'schools' ] as $this_school ) $result .= '<strong>' . $this_school['display_name'] . '</strong><br />';
	$result .= "</p>\n</div>";
	$result .= "</div>\n</div>\n</div>";
	
	return $result;
}

// Format Affiliate wells, including classes and attributes.
// Add key that contains well markup to $affiliates array and return 
// augmented array.
// 
function get_affiliate_wells( $the_affiliates ) {

	foreach ( $the_affiliates as $this_affiliate) {
	
		//target class allows sidebar controls to show hide schools and departments
		$target_class = ' filterz-target filterz-' . implode( ' filterz-' , array_keys( $this_affiliate[ 'schools' ] ) );
		if ( count( $this_affiliate[ 'depts' ] ) ) $target_class .= ' filterz-' . implode( ' filterz-' , array_keys( $this_affiliate[ 'depts' ] ) );
		
		//sortablz data fields allow toggles to control order of affiliates
		$sortablz_class = " data-last='" . $this_affiliate['last_name'] . "' ";
		$sortablz_class .= ( !empty( $this_affiliate[ 'idies_title' ] ) ) ? " data-title='" . $this_affiliate['idies_title'] . "' " : "";
		if ( count( $this_affiliate['schools'] ) ) {
			$school_keys = array_keys( $this_affiliate['schools'] );
			$sortablz_class .= " data-school='" . $this_affiliate[ 'schools' ][$school_keys[0]][ 'display_name' ] . "' ";
		}
		if ( count( $this_affiliate['depts'] ) ) {
			$dept_keys = array_keys( $this_affiliate['depts'] );
			$sortablz_class .= " data-dept='" . $this_affiliate[ 'depts' ][$dept_keys[0]][ 'display_name' ] . "' ";
		}
		$this_affiliate['well'] = get_affiliate_well( $this_affiliate , $target_class , $sortablz_class );
		$new_affiliates[] = $this_affiliate;
	}
	
	return $new_affiliates;
}
// 

//Show the Affiliate Search Bar
function show_affil_search( $options = array() , $this_option , $pane="people") {
}
