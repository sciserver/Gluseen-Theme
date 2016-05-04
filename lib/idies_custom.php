<?php
/*
 *****************************************
 SPEAKERS AND SYMPOSIUM 
 *****************************************
 */
 
/*
 *
 * Get Speaker Bio Info structure
 * 
 */
function get_speaker_cfc_form( $the_cfc ) {

	$speaker_cfc_post = get_posts( array ('name' => $the_cfc , 
										'post_type' => 'wck-meta-box',
										'numberposts' => -1 ,
										) );
	$speaker_cfc_post_meta_args = get_post_meta( $speaker_cfc_post[0]->ID , 'wck_cfc_args' , true);
	$speaker_cfc_post_meta_fields = get_post_meta( $speaker_cfc_post[0]->ID , 'wck_cfc_fields' , true);	

	$this_cfc_form['ID'] = $speaker_cfc_post[0]->ID;
	$this_cfc_form['repeater'] = $speaker_cfc_post_meta_args[0]['repeater'];
	$this_cfc_form['fields'] = $speaker_cfc_post_meta_fields;
	
	return $this_cfc_form;
	
}

/*
 *
 * Get Speaker Bio Info from meta data
 * 
 */
function get_speaker_bio_meta( $this_speaker_bio_id , $this_cfc ) {

	switch ($this_cfc) {
	
		case 'biography-meta':
		
			// Get speaker info from $this_cfc cfc in 'speaker' cpt
			$this_speaker['display-name'] = get_cfc_field( $this_cfc , 'display-name' , $this_speaker_bio_id );
			$this_speaker['degrees'] = get_cfc_field( $this_cfc , 'degrees' , $this_speaker_bio_id );
			$this_speaker['other-degrees'] = get_cfc_field( $this_cfc , 'other-degrees' , $this_speaker_bio_id );
			$this_speaker['talk-title'] = get_cfc_field( $this_cfc , 'talk-title' , $this_speaker_bio_id );
			$this_speaker['title'] = get_cfc_field( $this_cfc , 'title' , $this_speaker_bio_id );
			$this_speaker['other-titles'] = get_cfc_field( $this_cfc , 'other-titles' , $this_speaker_bio_id );
			$this_speaker['departments-or-centers'] = get_cfc_field( $this_cfc , 'departments-or-centers' , $this_speaker_bio_id );
			$this_speaker['other-affiliations'] = get_cfc_field( $this_cfc , 'other-affiliations' , $this_speaker_bio_id );
			$this_speaker['division-or-school'] = get_cfc_field( $this_cfc , 'division-or-school' , $this_speaker_bio_id );
			$this_speaker['institutional-affiliation'] = get_cfc_field( $this_cfc , 'institutional-affiliation' , $this_speaker_bio_id );
			$this_speaker['twitter'] = get_cfc_field( $this_cfc , 'twitter' , $this_speaker_bio_id );
			$this_speaker['facebook'] = get_cfc_field( $this_cfc , 'facebook' , $this_speaker_bio_id );
			$this_speaker['website'] = get_cfc_field( $this_cfc , 'website' , $this_speaker_bio_id );
			$this_speaker['other-social-media-urls'] = get_cfc_field( $this_cfc , 'other-social-media-urls' , $this_speaker_bio_id );
			$this_speaker['proposal'] = get_cfc_field( $this_cfc , 'proposal' , $this_speaker_bio_id );
			$this_speaker['biography'] = get_cfc_field( $this_cfc , 'biography' , $this_speaker_bio_id );
			$this_speaker['comments'] = get_cfc_field( $this_cfc , 'comments' , $this_speaker_bio_id );
			$this_speaker['notes'] = get_cfc_field( $this_cfc , 'notes' , $this_speaker_bio_id );
			$this_speaker['status'] = get_cfc_field( $this_cfc , 'status' , $this_speaker_bio_id );
			break;
			
		case 'profile-picture-meta' :
		case 'presentations-meta' :
		
			// probably a repeater cfc and has upload info
			// just get it all for now
			$this_speaker = get_cfc_meta( $this_cfc , $this_speaker_bio_id ) ;
			
			//for uploads also get the name and date of the file.
			foreach( get_cfc_meta(  $this_cfc , $this_speaker_bio_id ) as $upload_key => $upload_value ) {
				$this_speaker[$upload_key]['post_date'] = '';
				$this_speaker[$upload_key]['post_name'] = '';
				
				foreach( $upload_value as $field_key => $field_value ) {
					if ( ( strcmp($field_key,'upload') === 0 ) && ( $field_value ) ) {
						$this_upload_post = get_post( $field_value );
						if ( !empty( $this_upload_post ) ) {
							$this_speaker[$upload_key]['post_date'] = $this_upload_post->post_date;
							$this_speaker[$upload_key]['post_name'] = $this_upload_post->post_name;
						} else {
							//post wasn't found - ensure value is 0
							$this_speaker[$upload_key]['upload'] = '';
						}
					}
				}
			}
			
			break;
	}

	return $this_speaker;
}

/*
 *
 * Get Speaker Bio Info from meta data
 * 
 */
function clean_speaker_bio_meta( $this_speaker , $this_cfc = 'biography-meta') {

	if (strcmp( 'biography-meta' , $this_cfc ) !== 0 ) return $this_speaker;
	
	$this_speaker['degrees'] = idies_combine_fields( $this_speaker['degrees'] , $this_speaker['other-degrees'] , 'Degrees and Other Degrees' );
	$this_speaker['title'] = idies_combine_fields( $this_speaker['title'] , $this_speaker['other-titles'] , 'Title and Other Titles' );
	$this_speaker['departments-or-centers'] = idies_combine_fields( $this_speaker['departments-or-centers'] , $this_speaker['other-affiliations'] , 'Departments/Centers and Other Affiliation' );
	$this_speaker['division-or-school'] =  idies_combine_fields( $this_speaker['division-or-school'] , '' , 'Division/School' );
	$this_speaker['institutional-affiliation'] =  idies_combine_fields( $this_speaker['institutional-affiliation'] , '' , 'Institution' );
	
	return $this_speaker;
}

/*
 *
 * Combine Speaker Biography Fields, and post an warning 'Empty'  if necessary.
 * This allows for combining multi-select lists, radios, etc., with optional write in text fields.
 * 
 */
function idies_combine_fields( $first_var , $second_var , $field_title ) {	
	
	if ( !empty( $first_var ) ) {	
		if ( is_array( $first_var ) ) $first_var = implode( ', ', $first_var );
		if (!empty( $this_speaker['other_degrees'] ) ) {
			if ( is_array( $second_var ) ) $second_var = implode( ', ', $second_var );
			$first_var .= ', ' . $second_var ;
		} 
	} elseif ( !empty( $second_var ) ) {
		$first_var = $second_var ;
	} else {
		$first_var = empty_warning(  );
	}
	return $first_var;
	
}

/*
 * If field is Empty or other error, display warning.
 */
function empty_warning( $status = 'Empty' ) {
	return "<span class='text-warning'><em> - $status - </em></span>";
}

/*
 * Print Debug values.
 */
function idies_debug( $var ) {
	if ( WP_DEBUG ) :
	
		echo "<PRE>";
		
		if (is_array($var)) :
			print_r($var);
		elseif (is_object($var)) :
			var_dump($var);
		else :
			print($var);
		endif;
		
		echo "</PRE>";
		
	endif;
	return;
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
 * Get the user's login if the current user has role: $idies_role. If user is not 
 * logged in or does not have the role: $idies_role, returns false.
 */
function get_idies_login( $idies_role ) {

	if ( !is_user_logged_in() ) return false;
	
	global $current_user;
	global $display_name;
	
	get_currentuserinfo();
	$currentUserRoles = $current_user->roles;
	$display_name = $current_user->data->display_name;
	
	if (in_array ( $idies_role , ($currentUserRoles) ) ) { 
		return true;
	} else {
		return false;
	}
}

/* 
 * Get the user id for this biography cpt. If speaker's user id is not set, returns false.
 */
function get_idies_speaker_id(  ) {

		$this_speaker_meta_data = get_cfc_field( 'biography-meta' , 'userid' , false ) ;
		if ( empty( $this_speaker_meta_data ) ) return false;
		if ( empty( $this_speaker_meta_data['ID'] ) ) return false;
		return $this_speaker_meta_data['ID'] ;
	
}

/* 
 * Get the speaker's bio page id. If speaker does not have a page yet, 
 * returns false.
 */
function get_idies_speaker_bio( $idies_id , $idies_cpt ) {

	$all_idies_login_cpt = get_posts( array( 'post_type' => $idies_cpt,
										'post_status' => 'publish' ,
										'numberposts' => -1 ,
									) 
								);

	foreach( $all_idies_login_cpt as $key => $value) {
		$idies_post_meta = get_post_meta( $value->ID , 'biography-meta');
		if (empty($idies_post_meta[0][0]['userid'])) continue;
		$this_user_id = $idies_post_meta[0][0]['userid'] ;
		if ( strcmp( $idies_id , $this_user_id ) === 0 ) return $value->ID ;
	}
	return false;	
	
}

function show_speaker_bio( $this_speaker , $this_cfc) {
	global $all_bio_cfcs;
	
	if (empty($this_speaker)) {
		echo empty_warning( 'No ' . array_search( $this_cfc , $all_bio_cfcs ) . ' found ') ;
		return false;
	} 
	
	switch ($this_cfc) {
	
		case 'biography-meta':
?>
			<div class="row">
				<div class="col-xs-12">
					<p><strong>Name: </strong><?php echo $this_speaker['display-name']; ?></p>
					<p><strong>Degrees: </strong><?php echo join(', ',$this_speaker['degrees']); ?></p>
					<p><strong>Other Degrees: </strong><?php echo $this_speaker['other-degrees']; ?></p>
					<p><strong>Talk Title: </strong><?php echo $this_speaker['talk-title']; ?></p>
					<p><strong>Title: </strong><?php echo $this_speaker['title']; ?></p>
					<p><strong>Other Titles: </strong><?php echo $this_speaker['other-titles']; ?></p>
					<p><strong>Department or Center: </strong><?php echo join(', ',$this_speaker['departments-or-centers']); ?></p>
					<p><strong>Other Affiliations: </strong><?php echo $this_speaker['other-affiliations']; ?></p>
					<p><strong>Division or School: </strong><?php echo join(', ',$this_speaker['division-or-school']); ?></p>
					<p><strong>Institution: </strong><?php echo $this_speaker['institutional-affiliation']; ?></p>
					<p><strong>Biography: </strong><?php echo $this_speaker['biography']; ?></p>
					<?php //if ( strcmp( $this_speaker['biography'] , wp_trim_words( $this_speaker['biography'] , 40) ) !== 0 ) : ?>
					<?php //echo "<p>" . empty_warning('Overflow') . "</p>" ; ?>
					<?php //elseif ( empty( $this_speaker['biography'] ) ) : ?>
					<?php //echo "<p>" . empty_warning( ) . "</p>" ; ?>
					<?php //endif ; ?>
					<p><strong>Proposal (Seed Fund Awardees) only: </strong><?php echo $this_speaker['proposal']; ?></p>
					<p><strong>Social Media and Contact Info:</strong></p>
					<dl class="dl-horizontal">
					<?php if (! empty($this_speaker['twitter']) ) echo "<dt>Twitter: </dt><dd><a href='" . $this_speaker['twitter'] . "' target='_blank' >" . $this_speaker['twitter'] . "</a></dd>"; ?>
					<?php if (! empty($this_speaker['facebook']) ) echo "<dt>Facebook: </dt><dd><a href='" . $this_speaker['facebook'] . "' target='_blank' >" . $this_speaker['facebook'] . "</a></dd>"; ?>
					<?php if (! empty($this_speaker['website']) ) echo "<dt>Website: </dt><dd><a href='" . $this_speaker['website'] . "' target='_blank' >" . $this_speaker['website'] . "</a></dd>"; ?>
					<?php if (! empty($this_speaker['social']) ) echo "<dt>Other: </dt><dd><a href='" . $this_speaker['social'] . "' target='_blank' >" . $this_speaker['social'] . "</a></dd>"; ?>
					</dl>
					<div class="clearfix"></div>
				</div>
		<?php if ( !empty( $this_speaker['comments'] ) ) : ?>
				<div class="col-xs-12">
				<div class='panel panel-default'>
				<div class='panel-heading'>Comments from the Speaker to IDIES.</div> 
				<div class='panel-body'><em><?php echo $this_speaker['comments']; ?></em></div>
				</div>
				</div>
		<?php endif; ?>
			
		<?php if ( !empty( $this_speaker['notes'] ) ) : ?>
					<div class="col-xs-12">
					<div class='panel panel-info'>
					<div class='panel-heading'>Notes from IDIES to the Speaker: </div> 
					<div class='panel-body'><em><?php echo $this_speaker['notes']; ?></em></div>
					</div>
					</div>
		<?php endif ; ?>
				<p><strong>Status: </strong><?php echo $this_speaker['status']; ?></p>			
					
			</div>
<?php 
			break;
			
		case 'profile-picture-meta' :
		case 'presentations-meta' : 
?>
<table class="table table-condensed">
<tr><th>Media</th>
<th>Other Information</th>
<th>Delete file?</th></tr>
<?php 
			$this_speaker_attachment_number = 0;
			foreach( $this_speaker as $this_speaker_attachment) :
				if (!empty($this_speaker_attachment['upload'])) {
					$this_upload_id = $this_speaker_attachment['upload']; 
					$this_upload = get_post( $this_speaker_attachment['upload'] ); 
					$this_title = get_the_title( $this_speaker_attachment['upload'] );
				} else {
					$this_upload_id=-1;
					$this_upload='';
					$this_title='';
				}
				if (!empty($this_speaker_attachment['download'])) {
					$this_download = $this_speaker_attachment['download']; 
				} else {
					$this_download='';
				}
				if (!empty($this_speaker_attachment['comments'])) {
					$this_comments = $this_speaker_attachment['comments']; 
				} else {
					$this_comments='';
				}
				if (!empty($this_speaker_attachment['notes'])) {
					$this_notes = $this_speaker_attachment['notes']; 
				} else {
					$this_notes='';
				}
?>
<tr><td><?php 
				if (!empty($this_upload)) echo wp_get_attachment_link( $this_upload_id ); 
				if (!empty($this_title)) echo "<p class='text-center'><strong>Title: " . $this_title . "</strong></p>"; ?></td>
<td><?php 
				if (!empty($this_download)) echo "<strong>Link: </strong><a href='" . $this_download . "' target='_blank'>" . $this_download . "</a><br>"; 
				if (!empty($this_comments)) echo "<strong>Comments from the Speaker to IDIES: </strong>" . $this_comments . "<br>"; 
				if (!empty($this_notes)) echo "<strong>Notes to the Speaker from IDIES: </strong>" . $this_notes . "<br>"; ?></td>
<td class="text-center"><a href="?action=delete&target=<?php echo $this_upload_id; ?>&which=<?php echo $this_speaker_attachment_number++;?>&cfc=<?php echo $this_cfc;?>"><span class="glyphicon glyphicon-remove-sign"></span></a></td>
</tr>
<?php
				endforeach ; 
?></table><?php 
			break;
	}
	return true; 
}

/* 
 * Get the status of the speaker's bio page completion. 
 * We are only looking at $this_post_meta[0][0]['status']. 
 */
function get_speaker_status( $this_speaker_id , $this_bio_id, $this_cfc ) {
		
	// Get user info and post meta
	$this_user = get_user_by( 'id' , $this_speaker_id );
	$this_post_meta = get_post_meta( $this_bio_id , $this_cfc);
	
	// Populate Status Array
	$this_status_array['display_name'] = $this_user->data->display_name ;
	
	// If the status doesn't exist at all, the form hasn't been started. 
	// If it exists, the form has been started. 
	// If it's filled in with anything other than Not Started, keep the status.
	if ( empty( $this_post_meta[0] ) ) : $this_status_array['status'] = "Not Started" ;
	elseif ( empty( $this_post_meta[0][0] ) ) : $this_status_array['status'] = "Not Started" ;
	elseif ( empty( $this_post_meta[0][0]['status'] ) ) : $this_status_array['status'] = "Started" ;
	elseif ( strcmp( 'Not Started' , $this_post_meta[0][0]['status'] ) === 0 ) : $this_status_array['status'] = "Started" ;
	else : $this_status_array['status'] = $this_post_meta[0][0]['status'] ;
	endif ;
	
	$this_status_array['notes'] = ( empty( $this_post_meta[0][0]['notes'] ) ) ? '' : $this_post_meta[0][0]['notes'] ;

	switch ($this_status_array['status']) {
		case 'Not Started' :
			$this_status_array['panel'] = 'panel-danger'; 
			$this_status_array['glyph'] = 'glyphicon-exclamation-sign'; 
			$this_status_array['msg'] = 'Not Started'; 
			break;
		case 'Started' :
			$this_status_array['panel'] = 'panel-info'; 
			$this_status_array['glyph'] = 'glyphicon-question-sign'; 
			$this_status_array['msg'] = 'Content added. Not reviewed.'; 
			break;
		case 'In Progress' :
			$this_status_array['panel'] = 'panel-warning'; 
			$this_status_array['glyph'] = 'glyphicon-info-sign'; 
			$this_status_array['msg'] = 'Possible issue. Please check notes below.'; 
			break;
		case 'Complete' :
			$this_status_array['panel'] = 'panel-success'; 
			$this_status_array['glyph'] = 'glyphicon-ok-sign'; 
			$this_status_array['msg'] = 'Completed. Thanks you!'; 
			break;
		default:
			$this_status_array['panel'] = 'panel-default';
			$this_status_array['glyph'] = 'option-horizontal';
			$this_status_array['msg'] = '';
	}
		
	return $this_status_array;

}

/* 
 * Show a single form element  - e.g. text, password.
 */
function idies_show_single_input( $this_type , $this_title = 'no-title' , $this_description , $this_required , $this__class , $this_value = '' ) {
	$this_required = ( strcmp( $this_required , 'true' ) === 0) ; 
	$this_size = ( strcmp( $this_type , 'textarea' ) === 0) ? " rows='3' cols='90' " : " size='90' " ; 
?>
	<div class="row">
		<div class="col-xs-12">
			<label class="h4 <?php echo $this__class; ?>" 
				for="<?php echo $this__class; ?>"> 
				<?php echo $this_title; ?>: 
			</label><br>
			<span class="text-muted"><em><?php echo $this_description; ?>
			<?php if ( $this_required  ) echo '<span class="text-warning">* <sup>Required</sup></span>'; ?>
			</em></span>
		</div>
		<div class="col-xs-12">
			<?php if (strcmp( $this_type , 'textarea' ) === 0) : ?>
				<input type="hidden" name="<?php echo $this__class; ?>-key" value="<?php echo sanitize_title($this_title); ?>" >
				<textarea id="<?php echo $this__class; ?>" 
					name="<?php echo $this__class; ?>" 
					<?php echo $this_size; ?>				
					<?php if ( $this_required ) echo ' required '; ?>><?php echo $this_value; ?></textarea>
			<?php elseif (strcmp( $this_type , 'file' ) === 0) : ?>
				<input type="hidden" name="<?php echo $this__class; ?>-key" value="<?php echo sanitize_title($this_title); ?>" >
				<input type="<?php echo $this_type; ?>" id="<?php echo $this__class; ?>" 
				name="<?php echo $this__class; ?>" <?php if ( $this_required ) echo ' required '; ?> >
			<?php else : ?>
				<input type="hidden" name="<?php echo $this__class; ?>-key" value="<?php echo sanitize_title($this_title); ?>" >
				<input type="<?php echo $this_type; ?>" id="<?php echo $this__class; ?>" 
					name="<?php echo $this__class; ?>" value="<?php echo $this_value; ?>" 
					<?php echo $this_size; ?> <?php if ( $this_required ) echo ' required '; ?>>
			<?php endif; ?>
		</div>
	</div>
<?php
}

/* 
 * Show a form element with multiple options - e.g. checkbox, radio, select
 */
function idies_show_multiple_input( $this_type , $this_title , $this_description , $this_required , $this__class, $these_options , $these_values = '' ) {
	$this_required = ( strcmp( $this_required , 'true' ) === 0) ; 
	if (!is_array($these_values)) $these_values = array($these_values);
?>
	<div class="row">
		<div class="col-xs-12">
			<label class="h4 <?php echo $this__class; ?>" 
				for="<?php echo $this__class; ?>">
				<?php echo $this_title; ?>: 
			</label><br>
			<span class="text-muted"><em><?php echo $this_description; ?>
			<?php if ( $this_required  ) echo '<span class="text-warning">* <sup>Required</sup></span>'; ?>
			</em></span>
		</div>
	<?php if ( strcmp( $this_type , 'select' ) === 0) :?>
			<div class="col-xs-12">
				<input type="hidden" name="<?php echo $this__class; ?>-key" value="<?php echo sanitize_title($this_title); ?>" >
				<select name="<?php echo $this__class; ?>" >
				<?php foreach ( explode(',',$these_options) as $this_option ) : ?>
				<?php $selected = in_array( $this_option , $these_values ) ? 'selected' : '' ; ?>
						<option value="<?php echo $this_option; ?>" 
						<?php echo $selected; ?>><?php echo $this_option; ?></option>
				<?php endforeach; ?>
				</select>
			</div>
	<?php else : // probably a checkbox - can select multiple options ?>
		<?php foreach ( explode(',',$these_options) as $this_option ) : ?>
			<?php $selected = in_array( $this_option , $these_values ) ? 'checked' : '' ; ?>
			<div class="row">
			<?php $this_option = trim($this_option); ?>
			<?php if (empty($this_option)) continue; ?>
				<div class="col-xs-2 text-right">
				<input type="hidden" name="<?php echo $this__class . '-' . sanitize_title($this_option); ?>-key" value="<?php echo sanitize_title($this_title); ?>" >
				<input type="<?php echo $this_type; ?>" 
					id="<?php echo $this__class . '-' . sanitize_title($this_option) ; ?>" 
					name="<?php echo $this__class . '-' . sanitize_title($this_option) ; ?>" 
					value="<?php echo $this_option; ?>"  <?php echo $selected; ?> >
				</div>
				<div class="col-xs-10"><?php echo $this_option; ?></div>
			</div>
		<?php endforeach; ?>
	<?php endif ;?>
	</div>
<?php
}

/* 
 * Process the input form data
 */
function get_updated_bio_meta( $the_cfc_form , $form_id=0) {

	// loop through field titles and find the key that goes to them, then 
	// find the value(s) that go with that key in the post array. 
	$the_form_data = $_POST;
		
	foreach ( $the_cfc_form['fields']  as $this_cfc_form_field ) {
		
		$this_cfc_form_field_title = sanitize_title($this_cfc_form_field['field-title']);
		$this_cfc_form_field_type = $this_cfc_form_field['field-type'];
		$this_cfc_form_field_options = $this_cfc_form_field['options'];
		$this_cfc_form_field_default = $this_cfc_form_field['default-value'];
		
		$updated_meta[ $this_cfc_form_field_title ] = array(  );
		
		//find all the form data keys that have this field title's value (as slug)
		foreach ( array_keys( $the_form_data, $this_cfc_form_field_title) as $possible_title) {
			
			// if it ends with (*)-key, see if there are form-data keys $1
			// possible_key is the title
			//if ( strlen( $possible_key = rtrim( $possible_title, "-key" ) ) !== strlen( $possible_title ) ) {
			if ( strrpos( $possible_title, "-key", -4 ) !== false ) {
				$possible_key  = substr_replace( $possible_title , '' , -4 );

				//See if there is a value for this key
				if ( array_key_exists ( $possible_key , $the_form_data ) ) {
					$possible_value = trim( $the_form_data[ $possible_key ]);
					if ( !empty( $possible_value ) ) $updated_meta[ $this_cfc_form_field_title ][] = $possible_value;
				}
			}
		}
	}
	
	//Handle a file upload here
	// These files need to be included as dependencies when on the front end.
	if (!empty($_FILES["upload"])) {
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/media.php' );

		$possible_value = media_handle_upload( 'upload' , $form_id );
		if ( is_wp_error( $possible_value ) ) {
			idies_report_error("There was a problem with your upload.");
		} else {
			$updated_meta[ 'upload' ][] = $possible_value;
		}
	}
	
	foreach ( $updated_meta  as $this_updated_key => $this_updated_value ) {
		$updated_meta[$this_updated_key] = join( ', ', $updated_meta[ $this_updated_key ] );
	}

	return $updated_meta;
}

/* 
 * Process the input form data for biography-meta cfc.
 * This will be different for repeater fields like presentations and profile pics.
 */
function set_updated_bio_meta( $form_data , $bio_meta_post_id , $current_user_cfc , $ignore_fields_titles = array() , $status = '' , $append=false ) {

	$old_speaker_post_meta = get_post_meta( $bio_meta_post_id , $current_user_cfc , true);
	$new_speaker_post_meta = empty($old_speaker_post_meta) ? array(  ) : $old_speaker_post_meta ;
	if (!$append) {
		
		// loop through the speaker post data and update the values, ignoring as required
		foreach ( $old_speaker_post_meta as $outerkey =>$outervalue ) {
			foreach ( $outervalue as $key =>$value ) {
				if ( ( !in_array( $key , $ignore_fields_titles ) ) &&  ( array_key_exists( $key , $form_data ) ) ) {

					// update fields that are in the form
					$new_speaker_post_meta[$outerkey][$key] = $form_data[ $key ];
					
				} 
				if ( !empty( $status ) ) {
					if ( empty( $new_speaker_post_meta[$outerkey]['status'] ) || (strcmp( $new_speaker_post_meta[$outerkey]['status'] , 'Not Started') === 0 )) {
						$new_speaker_post_meta[$outerkey]['status'] = $status ;
					}
				}
			}
		}

		if ( strcmp( maybe_serialize( $new_speaker_post_meta ) , maybe_serialize( $old_speaker_post_meta ) ) === 0 ) return true;
		return update_metadata( 'post' , $bio_meta_post_id , $current_user_cfc , $new_speaker_post_meta , $old_speaker_post_meta );
		
	} else {
		
		$new_speaker_post_meta[  ] = $form_data;

		if ( strcmp( maybe_serialize( $new_speaker_post_meta ) , maybe_serialize( $old_speaker_post_meta ) ) === 0 ) return true;
		return update_metadata( 'post' , $bio_meta_post_id , $current_user_cfc , $new_speaker_post_meta , $old_speaker_post_meta );
	
	}
	return false ; // should not get here!
}

/*
 *
 * Delete a Speaker upload from meta data
 * 
 */
function delete_speaker_upload( $this_attachment_id, $this_attachment_indx, $bio_meta_post_id , $current_user_cfc ) {
	
	//get the postmeta data array
	$old_speaker_post_meta = get_post_meta( $bio_meta_post_id , $current_user_cfc , true);
	$new_speaker_post_meta = $old_speaker_post_meta;

	//find and delete that element of the postmeta data array
	if ( $this_attachment_id == -1 ) {
		array_splice($new_speaker_post_meta, $this_attachment_indx, 1);
	} else {
	
		foreach ( $old_speaker_post_meta as $outerkey =>$outervalue ) {
			if ( ( $outervalue['upload'] == $this_attachment_id ) ) {
				array_splice($new_speaker_post_meta, $outerkey, 1);
				break; 
			}
		}
	}
	
	//delete the post
	if ( strcmp( maybe_serialize( $new_speaker_post_meta ) , maybe_serialize( $old_speaker_post_meta ) ) === 0 ) return true;
	$success = update_metadata( 'post' , $bio_meta_post_id , $current_user_cfc , $new_speaker_post_meta , $old_speaker_post_meta );

	if (!$success) if (idies_report_error('An unexpected error has occurred.') ) return false;
		
	if ( $this_attachment_id > 0 ) {
		if (false === wp_delete_attachment( $this_attachment_id ) ) if (idies_report_error('A strange error has occurred.') ) return false;
	}

	return $success;
}

/* 
 * Process the input form data for biography-meta cfc.
 * This might be different for repeater fields like presentations and profile pics.
 * Just don't try submitting for now!.
 */
function idies_report_error( $msg ){
	echo "<div class='well well-sm well-danger alignleft'>$msg</div>\n";
	return true;
}

// Add an IDIES dashboard Widget.
function idies_add_dashboard_widgets() {

	wp_add_dashboard_widget(
                 'idies_dashboard_widget',         // Widget slug.
                 'IDIES Shortcuts',         // Title.
                 'idies_dashboard_widget_contents' // Display function.
        );	
}
add_action( 'wp_dashboard_setup', 'idies_add_dashboard_widgets' );

/**
 * Add a button linking the Speaker To Do List to the Dashboard Widget.
 */
function idies_dashboard_widget_contents() {

	// Display whatever it is you want to show.
	echo "<div class='wrap'>";
	echo "<a href='http://test.idies.jhu.edu/symposium/speakers/' class='button-primary'>Speakers: Go to your To Do List</a>";
	echo "</div>";
	
}

/**
 * Aim the endraftinator ray at a post or page.
 */
function endraftinator_ray( $post_id ) {

	wp_update_post( array( $post_id , 'post_status' => 'draft', ) );

}

/*
*****************************************
AFFILIATES PAGES
*****************************************
**/

 /*
 *
 * Get Data About Affiliates
 * 
 */
function idies_get_affiliates( $orderby = 'last'  ) {
	
	$affiliate_name = -1; //for debugging
	
	$all_affiliate_info = array();

	$affiliate_args = array(
		'posts_per_page'   => -1,
		'offset'   		   => 0,
		'orderby'          => 'title',
		'order'            => 'ASC',
		'post_type'        => 'affiliate',
		'post_status'      => 'publish',
	);
	if 	( !$affiliates_array = get_posts( $affiliate_args ) ) return $all_affiliate_info;

	// get them all
	foreach( $affiliates_array as $this_affiliate ) {
	
		//check if getting all (-1) or a few
		if ( ( $affiliate_name === -1 ) || (in_array( $this_affiliate->post_name , $affiliate_name ) ) ) {		
		
			//idies_debug( $this_affiliate );
			
			$this_affiliate_meta = get_post_meta( $this_affiliate->ID , 'affiliate-details' , true);
			
			$affiliate_info['post_title'] = $this_affiliate->post_name;
			$affiliate_info['ID'] = $this_affiliate->ID;
			$affiliate_info['display_name'] = empty( $this_affiliate->post_title ) ? '' : $this_affiliate->post_title;
			$affiliate_info['last_name'] = empty( $this_affiliate_meta[0]['last-name'] ) ? '' : $this_affiliate_meta[0]['last-name'];
			$affiliate_info['email'] = empty( $this_affiliate_meta[0]['email-address'] ) ? '' : $this_affiliate_meta[0]['email-address'];
			$affiliate_info['url'] = empty( $this_affiliate_meta[0]['url'] ) ? '' : $this_affiliate_meta[0]['url'] ;
			$affiliate_info['phone'] = empty( $this_affiliate_meta[0]['phone-number'] ) ? '' : $this_affiliate_meta[0]['phone-number'];
			$affiliate_info['address'] = empty( $this_affiliate_meta[0]['campus-address'] ) ? '' : $this_affiliate_meta[0]['campus-address'] ;
			$affiliate_info['execcomm'] = empty( $this_affiliate_meta[0]['executive-committee'] ) ? false : true ;
			$affiliate_info['staff'] = empty( $this_affiliate_meta[0]['staff'] ) ? false : true ;
			$affiliate_info['idies_title'] = empty( $this_affiliate_meta[0]['idies-title'] ) ? '' : $this_affiliate_meta[0]['idies-title'] ;
			
			$get_depts = array();
			$get_schools = array();

			foreach ( get_cfc_meta( 'dept-center-affiliations' ,  $affiliate_info['ID'] ) as $key => $value) {
			
				// get dept info from cfc data
				$this_dept = get_cfc_field( 'dept-center-affiliations' , 'department-or-center' , $affiliate_info['ID'] , $key );
				$this_sch = get_cfc_field( 'department-details' , 'schooldivision' , $this_dept->ID );
				
				// save dept, but skip if school and dept are same . i.e. Sheridan Libraries
				if( !( strcmp( $this_dept->post_title , $this_sch->post_title ) === 0 ) ) {
				
					//key is class name, value array holds ID and display name
					$get_depts[ 'dept-' . $this_dept->ID ] = array( 'ID' => $this_dept->ID ,
																	'display_name' => $this_dept->post_title );
				}
				
				// save school, but skip if already saved (i.e. more than one dept in same school)
				if ( !in_array( 'sch' . $this_sch->ID , $get_schools ) ) {
					//key is class name, value array holds ID and display name
					$get_schools[ 'sch-' . $this_sch->ID ] = array( 'ID' => $this_sch->ID ,
																	'display_name' => $this_sch->post_title );
				}
			}
			$affiliate_info['depts'] = $get_depts;
			$affiliate_info['schools'] = $get_schools;
			
			$all_affiliate_info[$this_affiliate->post_name] = $affiliate_info;
			$affiliate_info = array();
		}
	}

	//idies_debug( $all_affiliate_info );

	//default is order by last_name
	switch ( $orderby ) {
		case 'dept':
			uasort( $all_affiliate_info , 'idies_sort_by_dept' );
		break;
		case 'school' :
			uasort( $all_affiliate_info , 'idies_sort_by_school' );
		break;
		case 'title' :
			uasort( $all_affiliate_info , 'idies_sort_by_title' );
		break;
		case 'last' :
		default:
			uasort( $all_affiliate_info , 'idies_sort_by_last' );
	}
	return $all_affiliate_info ;

}

/*
 *
 * Get Departments from Affiliates (not all departments, only ones with affiliates)
 * 
 */
function idies_get_departments( $all_affiliates ) {

	$all_department_info = array();

	foreach( $all_affiliates as $this_affiliate ) {
		foreach( $this_affiliate['depts'] as $this_dept_key=>$this_dept ){
	
			if ( !in_array( $this_dept_key , $all_department_info ) ) {
				$all_department_info[ $this_dept_key ] = $this_dept;
			}
		}
	}
	
	//idies_debug( $all_department_info );
	uasort( $all_department_info , 'idies_sort_by_display_name' );
	return $all_department_info ;
}

/*
 *
 * Get Schools from Affiliates (not all schools, just ones with affiliates)
 * 
 */
function idies_get_schools( $all_affiliates ) {

	$all_school_info = array();

	foreach( $all_affiliates as $this_affiliate ) {
		foreach( $this_affiliate['schools'] as $this_school_key=>$this_school ){
	
			if ( !in_array( $this_school_key , $all_school_info ) ) {
				$all_school_info[ $this_school_key ] = $this_school;
			}
		}
	}
	
	//idies_debug( $all_school_info );
	uasort( $all_school_info , 'idies_sort_by_display_name' );
	return $all_school_info ;
}

// Filter the affiliates array to only include affiliates with $filter, or,
// not include affiliates with filter.  
function idies_filter_affil( $the_affiliates , $filter , $is = true ){
	$result = array();
	foreach( $the_affiliates as $this_affiliate ) {
		if ( empty( $this_affiliate[ $filter ] ) xor $is ) $result[] = $this_affiliate;	
	}
	return $result;
}

// Works with uasort to custom sort the Affiliates associative array.
// Sorts by the last_name field.  
function idies_sort_by_last( $a , $b ) {

	// If last names are the same, sorts by display_name (essentially first name).
	if ( strcmp( $a['last_name'], $b['last_name'] ) === 0 ) {
		if ( strcmp( $a['display_name'], $b['display_name'] ) === 0 ) {
			return 0;
		} else {
			return ( strcmp( $a['display_name'] , $b['display_name'] ) < 0 ) ? -1 : 1;
		}
    }
    return ( strcmp( $a['last_name'] , $b['last_name'] ) < 0 ) ? -1 : 1;
}

// Works with uasort to custom sort the Affiliates associative array.
// Sorts by school.  
function idies_sort_by_school( $a , $b ) {

	//if neither has a school, sort by last name.
	//if only one is empty, put it last
	if ( !( count( $a['schools'] ) + count( $b['schools'] )  ) ) {
		return idies_sort_by_last( $a , $b );
	} elseif ( !(count( $a['schools'] ) ) ) {
		return 1;
	} elseif ( !(count( $b['schools'] ) ) ) {
		return 1;
	}

	$afirst = current( $a[ 'schools' ] );
	$bfirst = current( $b[ 'schools' ] );
	
	//if neither has a school, or both in same school, sort by last name.
	if ( strcmp( $afirst['display_name'], $bfirst['display_name'] ) === 0 ) {
		return idies_sort_by_last( $a , $b );
	}
	
	//sort by primary school
    return ( strcmp( $afirst['display_name'] , $bfirst['display_name'] ) < 0 ) ? -1 : 1;
}

// Works with uasort to custom sort the Affiliates associative array.
// Sorts by department.  
function idies_sort_by_dept( $a , $b ) {

	//if neither has a department, sort by last name.
	//if only one is empty, put it last
	if ( !( count( $a['depts'] ) + count( $b['depts'] )  ) ) {
		return idies_sort_by_last( $a , $b );
	} elseif ( !(count( $a['depts'] ) ) ) {
		return 1;
	} elseif ( !(count( $b['depts'] ) ) ) {
		return 1;
	}

	$afirst = current( $a[ 'depts' ] );
	$bfirst = current( $b[ 'depts' ] );
	
	//if both in same school, sort by last name.
	if ( strcmp( $afirst['display_name'], $bfirst['display_name'] ) === 0 ) {
		return idies_sort_by_last( $a , $b );
	}
	
	//sort by primary department
    return ( strcmp( $afirst['display_name'] , $bfirst['display_name'] ) < 0 ) ? -1 : 1;
}

// Works with uasort to custom sort the Affiliates associative array.
// Sorts by (IDIES) Title.  
function idies_sort_by_title( $a , $b ) {

	//if neither has a title, sort by last name.
	//if only one is empty, put it last.
	if ( !( count( $a['idies_title'] ) + count( $b['idies_title'] )  ) ) {
		return idies_sort_by_last( $a , $b );
	} elseif ( !(count( $a['idies_title'] ) ) ) {
		return 1;
	} elseif ( !(count( $b['idies_title'] ) ) ) {
		return 1;
	}

	//if both have same title, sort by last name.
	if ( strcmp( $a['idies_title'], $b['idies_title'] ) === 0 ) {
		return idies_sort_by_last( $a , $b );
	}
	
	//sort by primary department
    return ( strcmp( $a['idies_title'] , $b['idies_title'] ) < 0 ) ? -1 : 1;
}

// Works with uasort to custom sort the Departments associative array.
// Sorts by department display_name.  
// There should never be two departments with the same display name.
function idies_sort_by_display_name( $a , $b ) {

	//sort by display_name
    return ( strcmp( $a['display_name'] , $b['display_name'] ) < 0 ) ? -1 : 1;
}
