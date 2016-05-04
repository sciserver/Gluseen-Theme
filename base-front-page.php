<?php get_template_part('templates/head'); ?>
<body <?php body_class(); ?>>
<?php
	if (locate_template('templates/pre-header.php') != '') {
		get_template_part('templates/pre-header');
	}
	do_action('get_header' , $IDIES_Web->header_file);
    get_template_part('templates/'.$IDIES_Web->header_file);
  ?>
  <div class="wrap container-fluid" role="document">
    <div class="content row">
      <main class="main" role="main">
		<?php include roots_template_path(); ?>
      </main><!-- /.main -->
    </div><!-- /.content -->
  </div><!-- /.wrap -->

  
<?php
	get_template_part('templates/'.$IDIES_Web->footer_file); 
?>

  <?php wp_footer(); ?>

</body>
</html>
