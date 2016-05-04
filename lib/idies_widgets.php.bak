<?php
// Replaces the get_the_excerpt "more" text with a button
function idies_excerpt_more($more) {
       global $post;
	return '...<p><a class="btn btn-sm btn-primary" href="'. get_permalink($post->ID) . '"> Read More</a></p>';
}
add_filter('excerpt_more', 'idies_excerpt_more');

// use widgets_init action hook to execute custom function
add_action( 'widgets_init', 'idies_register_widgets' );

 //register our widget
function idies_register_widgets() {
    register_widget( 'idies_show_posts_by_tag_widget' );
}
class idies_show_posts_by_tag_widget extends WP_Widget {
 //process the new widget
    function idies_show_posts_by_tag_widget() {
        $widget_ops = array(
            'classname' => 'idies_show_posts_by_tag_widget_class',
            'description' => 'Display posts by tag.'
        );
        $this->WP_Widget( 'idies_show_posts_by_tag_widget', 'IDIES Show Posts by Tag or Category',
            $widget_ops );
    }
	
	  //build the widget settings form
    function form($instance) {
        $defaults = array( 'title' => ''  );
        $defaults = array( 'tag' => ''  );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = $instance['title'];
        $tag = $instance['tag'];
        ?>
        <p>Title: <input class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>"  type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
        <p>Tag: <input class="widefat" name="<?php echo $this->get_field_name( 'tag' ); ?>"  type="text" value="<?php echo esc_attr( $tag ); ?>" /></p>
        <?php
    }
	
	 //save the widget settings
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['tag'] = strip_tags( $new_instance['tag'] );

        return $instance;
    }
	
	//display the widget
    function widget($args, $instance) {
        extract($args);

        $title = empty( $instance['title'] ) ? '&nbsp;' : $instance['title'];
        $tag = empty( $instance['tag'] ) ? '&nbsp;' : $instance['tag'];

		$tag_query = new WP_Query( array(
			'post_type' => 'post' ,
			'posts_per_page' => 3 ,
			'orderby'=>'date' , 
			'tag' => $tag , 
			'post_status' => 'publish')
		);
		
        echo $before_widget;
		if ( $tag_query->have_posts() ) :
			echo "<div class='show-posts'>";
			echo "<h3>" . $title . "</h3>";
			while ( $tag_query->have_posts() ) : $tag_query->the_post();
				echo "<a href='" . get_permalink(  ) . "'>";
				echo "<h4>" . get_the_title(  ) . "</h4>";
				echo "</a>";
				echo "<div class='show-post-excerpt'><p>" . get_the_excerpt(  ) . "</p></div>";
			endwhile;
			echo "</div>";
		endif;
        echo $after_widget;
    }
	
	
}
?>
