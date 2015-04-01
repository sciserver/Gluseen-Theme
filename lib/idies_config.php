<?php
/*
 *
 * Show Speaker Bio of this WP_User Object.
 * The style should mimic the style used in the Annual Review publication, i.e. what it
 * will look like when we publish it.
 * 
 * If no data is entered for a field, display warning in distinctive style
 * 
 */
function display_speaker_profile($this_speaker_id, $this_speaker_bio_id = false ) {

	if ( !check_wck() ) return; 
	
	$this_speaker_info = get_user_by( 'id' , $this_speaker_id);
    $this_speaker_name = $this_speaker_info->display_name;
    $this_speaker_email = $this_speaker_info->user_email;
	
	//assemble degree list
	$speaker_degrees = get_cfc_field( 'biography-meta' , 'degrees' , $this_speaker_bio_id );
	$other_degrees = get_cfc_field( 'biography-meta' , 'other-degrees' , $this_speaker_bio_id );
	if ( !empty( $speaker_degrees ) ) {
		$speaker_degrees = implode( ', ', $speaker_degrees );
		if (!empty( $other_degrees ) ) {
			$speaker_degrees .= ', ' . $other_degrees ;
		} 
	} elseif (!empty( $other_degrees ) ) {
		$speaker_degrees = $other_degrees ;
	} else {
		$speaker_degrees = empty_warning( 'Degrees and Other Degrees' );
	}
	
	//assemble title list
	$speaker_titles = get_cfc_field( 'biography-meta' , 'title' , $this_speaker_bio_id );
	$other_titles = get_cfc_field( 'biography-meta' , 'other-titles' , $this_speaker_bio_id );
	if ( empty( $speaker_titles ) ) {
		if ( empty( $other_titles ) ) {
			$speaker_titles = empty_warning( 'Title and Other Titles' );
		} else {
			$speaker_titles = $other_titles ;
		}
	} elseif ( !empty( $other_titles ) ) {
		$speaker_titles .= ' and ' . $other_titles ;
	}
	
	//assemble department/center list
	$speaker_dept = get_cfc_field( 'biography-meta' , 'departments-or-centers' , $this_speaker_bio_id );
	$speaker_affil = get_cfc_field( 'biography-meta' , 'other-affiliations' , $this_speaker_bio_id );
	if ( !empty( $speaker_dept ) ) {
		$speaker_dept = implode( ', ', $speaker_dept );
		if ( !empty( $speaker_affil ) ) {
			$speaker_dept .= ', ' . $speaker_affil;
		}
	} elseif ( !empty( $speaker_affil ) ) {
		$speaker_dept = $speaker_affil;
	} else {
		$speaker_dept = empty_warning( 'Departments/Centers and Other Affiliation' );
	}
	
	// division/school list
	$speaker_div = get_cfc_field( 'biography-meta' , 'division-or-school' , $this_speaker_bio_id );
	if ( !empty( $speaker_div ) ) {
		$speaker_div = implode( ', ', $speaker_div );
	} else {
		$speaker_div = empty_warning( 'Division/School' );
	}
	
	// Institutional Affiliations
	$speaker_inst = get_cfc_field( 'biography-meta' , 'institutional-affiliation' , $this_speaker_bio_id );
	if ( empty( $speaker_inst ) ) {
		$speaker_div = empty_warning( 'Institution' );
	}
	
	//social medai
	$speaker_twitter = get_cfc_field( 'biography-meta' , 'twitter' , $this_speaker_bio_id );
	$speaker_facebook = get_cfc_field( 'biography-meta' , 'facebook' , $this_speaker_bio_id );
	$speaker_website = get_cfc_field( 'biography-meta' , 'website' , $this_speaker_bio_id );
	$speaker_social = get_cfc_field( 'biography-meta' , 'other-social-media-urls' , $this_speaker_bio_id );
	
	$speaker_about = get_cfc_field( 'biography-meta' , 'biography' , $this_speaker_bio_id );
	
	$speaker_comments = get_cfc_field( 'biography-meta' , 'additional-information' , $this_speaker_bio_id );
	if ( !empty( $speaker_comments ) ) $speaker_comments = 
			"<div class='panel panel-default'><div class='panel-heading'>The following " . 
			"comments provide us with instructions on rendering your biography, " . 
			"but are not included verbatim on your biography.</div> " . 
			"<div class='panel-body'><em>" . $speaker_comments . "</em></div></div>";
?>
	<h2><?php echo $this_speaker_name ;?> Biography:</h2>
	<div class="row">
		<a class="btn btn-small btn-primary alignright" href="<?php echo home_url() . '/wp-admin/post.php?post=' . $this_speaker_bio_id . '&action=edit'; ?>">Edit Biography</a>
		<div class="col-xs-12 col-sm-4 col-md-3"><img class="img-responsive" src="<?php the_cfc_field('biography-meta', 'profile-picture'  , $this_speaker_bio_id); ?>" /></div>
		<div class="col-xs-12 col-sm-8 col-md-9">
			<p><strong><?php echo $this_speaker_name; ?>, <?php echo $speaker_degrees; ?></strong></p>
			<p><?php echo $speaker_titles; ?></p>
			<p><strong>Department or Center: </strong><?php echo $speaker_dept; ?></p>
			<p><strong>Division or School: </strong><?php echo $speaker_div; ?></p>
			<p><strong>Institution: </strong><?php echo $speaker_inst; ?></p>
			<p><strong>Biography: </strong><?php echo wp_trim_words( $speaker_about , 40); ?></p>
			<p><?php if ( strcmp( $speaker_about , wp_trim_words( $speaker_about , 40) ) !== 0 ) : ?>
			<?php echo empty_warning('Biography','Overflow') ; ?>
			<p><?php elseif ( empty( $speaker_about ) ) : ?>
			<?php echo empty_warning('Biography') ; ?>
			<p><?php endif ; ?>
			</p>
			<p><strong>Social Media and Contact Info:</strong></p>
			<dl class="dl-horizontal">
			<dt>Email: </dt><dd><a href="mailto:<?php echo $this_speaker_email ;?>" ><?php echo $this_speaker_email ;?></a></dd>
			<?php if (! empty($speaker_twitter) ) echo "<dt>Twitter: </dt><dd><a href='" . $speaker_twitter . "' target='_blank' >" . $speaker_twitter . "</a></dd>"; ?>
			<?php if (! empty($speaker_facebook) ) echo "<dt>Facebook: </dt><dd><a href='" . $speaker_facebook . "' target='_blank' >" . $speaker_facebook . "</a></dd>"; ?>
			<?php if (! empty($speaker_website) ) echo "<dt>Website: </dt><dd><a href='" . $speaker_website . "' target='_blank' >" . $speaker_website . "</a></dd>"; ?>
			<?php if (! empty($speaker_social) ) echo "<dt>Other: </dt><dd><a href='" . $speaker_social . "' target='_blank' >" . $speaker_social . "</a></dd>"; ?>
			</dl>
			<div class="clearfix"></div>
		</div>
		<div class="col-xs-12">
			<?php echo $speaker_comments; ?>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<div class="text-warning"><em>Note: Your <span class="label label-default">Display Name Publicly As</span> and <span class="label label-default">Email</span> can only be changed on your WordPress profile. Please update as necessary. </em><a class="btn btn-small btn-primary alignright" href="http://test.idies.jhu.edu/symposium/wp-admin/profile.php">Edit WordPress Profile</a>
			</div>
		</div>
	</div>
	<?php
}
/*
 *
 * Edit Speaker Biography
 * 
 */
function edit_speaker_profile($this_speaker_id, $this_speaker_bio_id = false ) {

	if ( !check_wck() ) return; 
	
	// Get the speaker-bio/biography post id (227) from the wp_posts table
	$my_cpt_biography_meta = get_posts( array( 'name' => 'biography-meta', 'post_type' => 'wck-meta-box',) );
	$my_cpt_biography_meta_id = $my_cpt_biography_meta[0]->ID;
	
	// Get the speaker-bio/biography meta data (how to build the form) from the wp-postmeta table
	$my_cpt_biography_meta_values = get_post_meta( $my_cpt_biography_meta_id, 'wck_cfc_fields', false );
	
	// Get *this* speaker's biographical info
	$biography_meta = get_post( $this_speaker_bio_id , 'biography-meta' , true );
	
	$speaker_degrees = get_cfc_field( 'biography-meta' , 'degrees' , $this_speaker_bio_id );
	$other_degrees = get_cfc_field( 'biography-meta' , 'other-degrees' , $this_speaker_bio_id );
	$speaker_titles = get_cfc_field( 'biography-meta' , 'title' , $this_speaker_bio_id );
	$other_titles = get_cfc_field( 'biography-meta' , 'other-titles' , $this_speaker_bio_id );
	$speaker_dept = get_cfc_field( 'biography-meta' , 'departments-or-centers' , $this_speaker_bio_id );
	$speaker_affil = get_cfc_field( 'biography-meta' , 'other-affiliations' , $this_speaker_bio_id );
	$speaker_div = get_cfc_field( 'biography-meta' , 'division-or-school' , $this_speaker_bio_id );
	$speaker_inst = get_cfc_field( 'biography-meta' , 'institutional-affiliation' , $this_speaker_bio_id );
	$speaker_twitter = get_cfc_field( 'biography-meta' , 'twitter' , $this_speaker_bio_id );
	$speaker_facebook = get_cfc_field( 'biography-meta' , 'facebook' , $this_speaker_bio_id );
	$speaker_website = get_cfc_field( 'biography-meta' , 'website' , $this_speaker_bio_id );
	$speaker_social = get_cfc_field( 'biography-meta' , 'other-social-media-urls' , $this_speaker_bio_id );
	$speaker_about = get_cfc_field( 'biography-meta' , 'biography' , $this_speaker_bio_id );
	$speaker_comments = get_cfc_field( 'biography-meta' , 'additional-information' , $this_speaker_bio_id );

	// Render the Edit Speaker form
?>
	<h2>Edit Biography:</h2>
	<div class="row">
		<a class="btn btn-small btn-primary alignright" href="http://test.idies.jhu.edu/symposium/speakers/biography/">View Biography</a>
		<pre>
		<?php //print_r($biography_meta); ?>
		</pre>
	</div>
	<?php
}

/*
 * If field is Empty or other error, display warning.
 */
function empty_warning( $field_name, $status = 'Empty' ) {
	return "<span class='text-warning'><strong>Warning: $field_name $status.</strong></span>";
}

/*
 * Make sure WordPress Creative Kit is activated before using it!
 */
function check_wck() {
	if ( !function_exists( 'get_cfc_meta' ) )  { 
		echo "<!-- Warning: WCK Pro not installed -->"; 
		return false; 
	} else {
		return true;
	}
}

/*
 * Show a button link to the speaker biography below the color scheme and above username field on the Profile Page.
 */
add_action( 'profile_personal_options', 'speaker_profile_button' ); 
function speaker_profile_button( $user ) {

	global $current_user;
	get_currentuserinfo();
	$currentUserRoles = $current_user->roles;
	
	if (in_array ( 'speakers' , ($currentUserRoles) ) ) {     
?>
		<a href="http://test.idies.jhu.edu/symposium/speakers/biography/" style="border:1px solid #b43c38;background-color:#ccaf0b;color:black;font-size:14px;font-weight:bold;padding:5px;" type="submit">Click here to go to your Biography Page</a>
<?php
	}
}

function get_idies_speaker_notes( $section ) {
	return $section;
}

/* 
 * Get the user's login if the current user has role: $idies_role. If user is not 
 * logged in or does not have the role: $idies_role, returns false.
 */
function get_idies_login( $idies_role ) {

	global $current_user;
	get_currentuserinfo();
	$currentUserRoles = $current_user->roles;
	
	if (in_array ( $idies_role , ($currentUserRoles) ) ) { 
		return $current_user->ID;
	} else {
		return false;
	}
}

/* 
 * Get the speaker's bio page id. If speaker does not have a page yet, 
 * returns false.
 */
function get_idies_speaker_bio( $idies_id , $idies_cpt ) {

	
	$all_idies_login_cpt = get_posts( array( 'post_type' => $idies_cpt,) );
	foreach( $all_idies_login_cpt as $key => $value) {
		$idies_post_meta = get_post_meta( $value->ID , 'biography-meta');
		if ( strcmp( $idies_id , $idies_post_meta[0][0]['userid'] ) === 0 ) return $value->ID ;
	}
	return false;	
	
}
/* 
 * Get the status of the speaker's bio page completion. 
 */
function get_speaker_status( $this_speaker_id , $this_bio_id ) {

	$the_cfcs = array( 'biography-meta' , 'profile-picture-meta' , 'presentations-meta' );
	$this_user = get_user_by( 'id' , $this_speaker_id );
	$this_status_array['display_name'] = $this_user->data->display_name ;
	foreach( $the_cfcs as $value) {
		$this_post_meta = get_post_meta( $this_bio_id , $value);
		$this_status_array[$value.'-status'] = ( empty( $this_post_meta[0][0]['status'] ) ) ? 'Not Started' : $this_post_meta[0][0]['status'] ;
		$this_status_array[$value.'-notes'] = ( empty( $this_post_meta[0][0]['notes'] ) ) ? '' : $this_post_meta[0][0]['notes'] ;
	}
	return $this_status_array;
}