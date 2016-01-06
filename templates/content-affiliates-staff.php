<?php
/* 
 * Show affiliated people on the affiliates page. 
 */ 
echo "<!-- staff -->"; 

//which schools, centers, or departments to show. Default is all.
if ( ( $show_sch = get_query_var( 'idies_sch' , 'all' ) ) ) { 
	echo "<!-- " . $show_sch . " -->"; 
}
if ( ( $show_dept = get_query_var( 'idies_dept' , 'all' ) ) ) { echo "<!-- " . $show_dept . " -->"; }
if ( ( $show_cent = get_query_var( 'idies_cent' , 'all' ) ) ) { echo "<!-- " . $show_cent . " -->"; }

//idies_dept, idies_cent, idies_sch, idies_type
$all_affiliates = idies_get_affiliates(  );
$exec_comm = idies_get_exec_comm( $all_affiliates );

?>