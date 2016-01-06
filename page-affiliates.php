<?php 
/*  Starting point for the affiliates page. 
	Get query variables and use the people, centers, executive committee, or staff affiliates page template.
 */ 
get_template_part('templates/page', 'header'); 

// Find page to show from query var & check it
if ( ( $show_type = get_query_var( 'idies_type' , 'people' ) ) ) { 
	$allowed = array('people','centers','execcomm','staff');
	if ( !in_array($show_type , $allowed ) ) $show_type='people';
}

//Show the Requested Affiliates Page
switch ($show_type) {
	case('people'):
		echo "<!-- getting people -->"; 
		get_template_part('templates/content', 'affiliates-people');
		return;
	break;
	case('centers'):
		return;
		get_template_part('templates/content', 'affiliates-centers');
	break;
	case('execcomm'):
		return;
		get_template_part('templates/content', 'affiliates-execcomm');
	break;
	case('staff'):
		return;
		get_template_part('templates/content', 'affiliates-staff');
	break;
}

if ( ( $show_sch = get_query_var( 'idies_sch' , 'all' ) ) ) { 
	echo "<!-- " . $show_sch . " -->"; 
}
if ( ( $show_dept = get_query_var( 'idies_dept' , 'all' ) ) ) { echo "<!-- " . $show_dept . " -->"; }
if ( ( $show_cent = get_query_var( 'idies_cent' , 'all' ) ) ) { echo "<!-- " . $show_cent . " -->"; }

?>
</div>