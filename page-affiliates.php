<?php 
/*  Starting point for the affiliates page. 
	Get query variables and use the people, centers, executive committee, or staff affiliates page template.
 */ 
get_template_part('templates/page', 'header'); 

get_template_part('templates/content', 'affiliates');

return;

//Show the Requested Affiliates Page
switch ($show_type) {
	case('people'):
		echo "<!-- getting people -->"; 
		get_template_part('templates/content', 'affiliates-people');
		return;
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

?>
</div>