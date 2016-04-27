<header class="banner"  role="banner">
<div class="logo">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-6 alignleft">
			<a href="http://idies.jhu.edu"><img src="<?php echo get_bloginfo( 'wpurl' ); ?>/wp-content/themes/idiestheme/assets/img/idies-logo-big3.png" alt="IDIES Logo" class="img-responsive img-header-logo"></a>
			<h4>The Institute for Data Intensive Engineering and Science</h4>
			</div>
			<div class="col-xs-12 col-sm-6 alignright">
			<img src="<?php echo get_bloginfo( 'wpurl' ); ?>/wp-content/themes/idiestheme/assets/img/jhu_logo.png" alt="Johns Hopkins University" class="img-responsive img-header-hopkins">
			</div>
			</div>
	</div>
</div><!--end of logo -->
<div class="navbar navbar-default navbar-static-top">
<div class="container">
<div class="navbar-header">
<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
<span class="sr-only">Toggle navigation</span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
</button>
</div>

    <nav class="collapse navbar-collapse" role="navigation">
      <?php
        if (has_nav_menu('primary_navigation')) :
          wp_nav_menu(array('theme_location' => 'primary_navigation', 'menu_class' => 'nav navbar-nav'));
        endif;
      ?>
    </nav>
  </div>
  </div>
</header>