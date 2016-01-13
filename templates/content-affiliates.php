<?php
/* 
 * Show affiliated people on the affiliates page. 
 */ 
?>
<?php 
while (have_posts()) : the_post(); 
	the_content(); 
endwhile; 

// Show an affiliate in a formatted well.
function show_affiliate_well( $this_affiliate , $affil_class = "" ) {
	echo "<div class='$affil_class'>";
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
function show_orderby( $options = array() , $this_option ) {

	echo "<div class='form-horizontal orderby_options text-center panel panel-info'>";
	echo "<div class='panel-heading'>";
	echo "<div class='row '>";
	echo "<div class='col-sm-3 col-xs-12'><strong>Order by: </strong></div>";
	
	foreach ( $options as $this_option_value => $this_option_name ) {
	
		
		$checked = ( strcmp($this_option_value , $this_option) === 0 ) ? 'checked=true' : "";
		$this_id = "orderby_option_" . $this_option_value;
		$onchange = " onchange=\"document.location='" . get_permalink() . "?orderby=$this_option_value'\" ";
		echo "<div class='col-sm-3 col-xs-12'>";
		
		echo "<input type='radio' name='orderby_options' value='$this_option_value' $onchange $checked id='$this_id'>";
		
		echo "<label for='$this_id'>$this_option_name</label>";
		
		echo "</div>";
	}
	
	echo "</div>";
	echo "</div>";
	echo "</div>";
	
	return;
}

//Show the Affiliate Search Bar

// Find page to show from query var & check it
$orderby_options = array( 'last' => 'Last Name' , 'school' => 'School' , 'dept' => 'Department');
$orderby = get_query_var( 'orderby' , 'last' );

$show = get_query_var( 'idies_type' , 'people' );
if ( !in_array( $show , array( 'execcomm' , 'staff' ) ) ) $show = 'people';

// get and flatten schools, centers, depts, and affiliates (putting school info in depts and dept/center info in affiliates
$all_affiliates = idies_get_affiliates( $orderby );
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
		<div role="tabpanel" class="tab-pane active" id="people">
			<div class="row">
				<div class="col-sm-9 col-xs-12">
<?php	
					show_orderby( $orderby_options , $orderby ) ;
?>
					<div class='row dd2-targets'>
<?php
						foreach ( $people_affiliates as $this_affiliate) {
							$target_class = ' dd2-target dd2-sch-all dd2-' . implode( ' dd2-' , array_keys( $this_affiliate[ 'schools' ] ) );
							if ( count( $this_affiliate[ 'depts' ] ) ) $target_class .= ' dd2-dept-all dd2-' . implode( ' dd2-' , array_keys( $this_affiliate[ 'depts' ] ) );
							show_affiliate_well( $this_affiliate , $target_class );
						}
?>
					</div>
				</div>
				<div class="col-sm-3 hidden-xs">
					<div class="form-horizontal dd2-controls">
						<div class="panel panel-default">
							<div class="panel-heading"><h4>Schools <div class="alignright"><a role="button" data-toggle="collapse" href="#collapseSch" aria-expanded="false" aria-controls="collapseSch"><i class="fa fa-bars fa-3"></i></a></div></h4></div>
								<div class="panel-body collapse in" id="collapseSch">
									<div class="form-group">
										<div class="col-xs-2">
											<input type='checkbox' checked='checked'  role='toggle' data-toggle='dd2' data-target='.dd2-sch-all' id="dd2-sch-all">
										</div>
										<div class="col-xs-10">
											<label for="dd2-sch-all" class="bigger">All Schools</label>
										</div>
									</div>
<?php 
									// loop through all schools, display their name, checkbox (checked) and make them data-toggles for sch-#### 
									foreach($all_schools as $this_key=>$this_school) {
										echo '<div class="form-group"><div class="col-xs-2">';
										echo "<input type='checkbox' role='toggle' data-toggle='dd2' data-target='.dd2-$this_key' id='dd2-$this_key' > ";
										echo '</div><div class="col-xs-10">';
										echo "<label for='dd2-$this_key'>" . $this_school['display_name'] . " <span id='dd2-$this_key-count'></span></label>";
										echo '</div></div>';
									} 
?>
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
										<input type='checkbox' checked='checked' role='toggle' data-toggle='dd2' data-target='.dd2-dept-all' id="dd2-dept-all">
									</div>
									<div class="col-xs-10">
										<label for="dd2-dept-all" class="bigger"> All Departments</label><br/>
									</div>
								</div>
<?php
								// loop through all departments, display_name, checkbox (checked) and make them data-toggles for dept-#### 
								foreach($all_departments as $this_key=>$this_department) {
									echo '<div class="form-group"><div class="col-xs-2">';
									echo "<input type='checkbox' role='toggle' data-toggle='dd2' data-target='.dd2-$this_key' id='dd2-$this_key' > ";
									echo '</div><div class="col-xs-10">';
									echo "<label for='dd2-$this_key'>" . $this_department['display_name'] . " <span id='dd2-$this_key-count'></span></label><br/>";
									echo '</div></div>';
								}
?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	  <!-- Executive Committee pane -->
		<div role="tabpanel" class="tab-pane" id="execcomm">
			<div class="row">
				<div class="col-xs-12">
					<div class='row '>
<?php
						foreach ( $execcomm_affiliates as $this_affiliate) {
							show_affiliate_well( $this_affiliate );
						}
?>
					</div>
				</div>
			</div>
		</div>
	<!-- Staff pane -->
		<div role="tabpanel" class="tab-pane" id="staff">
			<div class="row">
				<div class="col-xs-12">
					<div class='row dd2-targets'>
<?php
						foreach ( $staff_affiliates as $this_affiliate) {
							show_affiliate_well( $this_affiliate );
						}
?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
