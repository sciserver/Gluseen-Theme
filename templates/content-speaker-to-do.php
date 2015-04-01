<?php
if ( !function_exists( 'get_cfc_meta' ) ) return;
$idies_todos = get_cfc_meta( 'speaker-to-do-meta' ); 
if ( !count( $idies_todos ) ) return;

// Check if user logged in and a speaker and has a biography to edit.
$current_user_id = get_idies_login( 'speakers' ) ;
if ( $current_user_id ) {
	$current_user_bio_id = get_idies_speaker_bio( $current_user_id , 'speaker-bio' );
	if ( empty( $current_user_bio_id ) ) {
	?>
		<div class="row"><div class="col-xs-6 col-md-12"><strong><span class="label label-danger medium">A Biography Page does not yet exist for you. Please contact the <a href="mailto:bsouter@jhu.edu">Editor</a>.</span></strong></div></div>
	<?php
		return;
	}
} else {
	?>
	<div class="row"><div class="col-xs-6 col-md-12"><strong><span class="label label-danger medium">Speakers must log in to view and or edit their biography and attachments.</span></strong></div></div>
	<?php
	return;
}

//Get To Do and Notes for this Speaker
$speaker_status = get_speaker_status( $current_user_id, $current_user_bio_id );
$idies_panel_context = array(
					'Not Started' => array(
						'panel' => 'panel-danger' , 
						'glyph' => 'glyphicon-exclamation-sign',
						'msg' => 'Not Started',
						) ,
					'Started' => array(
						'panel' => 'panel-info', 
						'glyph' => 'glyphicon-question-sign',
						'msg' => 'Content added. Not checked yet.',
						) ,
					'In Progress' => array(
						'panel' => 'panel-warning', 
						'glyph' => 'glyphicon-info-sign',
						'msg' => 'Possible issue. Please check notes below.',
						) ,
					'Complete' => array(
						'panel' => 'panel-success', 
						'glyph' => 'glyphicon-ok-sign',
						'msg' => 'Completed. Thank you!',
						) ,
					);
?>
<span class="text-info x-large"><em>Thank you</em></span> for agreeing to speak at the Institute for Data Intensive Engineering and Science's 2015 Symposium! 

<p>Please use these pages to edit your <strong>Biography</strong> and upload your <strong>Profile Picture</strong> and <strong>Presentation Files</strong> for the Symposium and <em>Annual Review</em> (a printed publication).</p>

<div class="h3"><?php echo $speaker_status['display_name']; ?>'s To Do List</div>

<p>The items below are your To Do list! </p>

<div class="clearfix"></div>
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
<?php
$todo_num = 1;
$todo_in = ' in ';
$todo_collapsed = '';
foreach( $idies_todos as $key => $value ) : 

	$todo_question = the_cfc_field('speaker-to-do-meta', 'title', false, $key, false);
	$todo_answer = the_cfc_field('speaker-to-do-meta', 'description', false, $key , false);
	$todo_link = the_cfc_field('speaker-to-do-meta', 'link', false, $key , false);
	$todo_slug = the_cfc_field('speaker-to-do-meta', 'slug', false, $key , false);
	$todo_cfc_slug = the_cfc_field('speaker-to-do-meta', 'cfc-slug', false, $key , false);
	$todo_action = get_query_var( 'action' );
	$todo_cfc = get_query_var( 'cfc' );
?>
<div class="panel <?php echo $idies_panel_context[$speaker_status[$todo_cfc_slug . '-status']]['panel']; ?>">
	<div class="panel-heading" role="tab" id="heading<?php echo $todo_num; ?>">
		<h4 class="panel-title" title="<?php echo $idies_panel_context[$speaker_status[$todo_cfc_slug . '-status']]['msg']; ?>"><span title="<?php echo $idies_panel_context[$speaker_status[$todo_cfc_slug . '-status']]['msg']; ?>" class="glyphicon <?php echo $idies_panel_context[$speaker_status[$todo_cfc_slug . '-status']]['glyph']; ?>"></span>
		<a data-toggle="collapse" class="<?php echo $todo_collapsed; ?>" data-parent="#accordion" href="#collapse<?php echo $todo_num; ?>" aria-expanded="true" aria-controls="collapse<?php echo $todo_num; ?>">
		<?php echo $todo_question; ?>
		</a>
		</h4>		
	</div>
	<div id="collapse<?php echo $todo_num; ?>" class="panel-collapse collapse <?php echo $todo_in; ?>" role="tabpanel" aria-labelledby="heading<?php echo $todo_num; ?>">
		<div class="panel-body">
			<div class="row">
				<div class="col-xs-6 col-md-9">
					<?php echo $todo_answer; ?>
				</div>
				<div class="col-xs-3 col-md-3">
					<a class="btn btn-primary alignright" href="<?php echo home_url() . $todo_slug; ?>">View <?php echo $todo_link; ?></a><br>
					<a class="btn btn-primary alignright" href="<?php echo home_url() . $todo_slug . '?action=edit&cfc=' . $todo_cfc_slug ; ?>">Edit <?php echo $todo_link; ?></a>
				</div>
				<div class="col-xs-12">
					<br>
					<?php if (!empty($speaker_status[$todo_cfc_slug .'-notes'])) echo '<span class="label label-danger medium" >' . $speaker_status[$todo_cfc_slug .'-notes'] . '</span>'; ?>
				</div>
			</div>
		</div>
	</div>
</div> 
<?php
	$todo_num++;
	$todo_in = '';
	$todo_collapsed = ' collapsed ';
endforeach; 
?>
</div> 
<div class="clearfix"></div>
