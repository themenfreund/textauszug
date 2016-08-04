/**
 * Featured Posts widget
 */
class colormag_featured_posts_widget extends WP_Widget {
 
   function __construct() {
      $widget_ops = array( 'classname' => 'widget_featured_posts widget_featured_meta', 'description' =>__( 'Display latest posts or posts of specific category.' , 'colormag') );
      $control_ops = array( 'width' => 200, 'height' =>250 );
      parent::__construct( false,$name= __( 'TG: Featured Posts (Style 1)', 'colormag' ),$widget_ops);
   }
 
   function form( $instance ) {
      $tg_defaults['title'] = '';
      $tg_defaults['text'] = '';
      $tg_defaults['number'] = 4;
      $tg_defaults['type'] = 'latest';
      $tg_defaults['category'] = '';
	  $tg_defaults['cat'] = sanitize_text_field($new_instance['cat']);
      $tg_defaults['summary'] = sanitize_text_field($new_instance['summary']);
	  $instance = wp_parse_args( (array) $instance, $tg_defaults );
      $title = esc_attr( $instance[ 'title' ] );
      $text = esc_textarea($instance['text']);
      $number = $instance['number'];
      $type = $instance['type'];
      $category = $instance['category'];
	  $cat = $instance['cat'];
	  $summary = $instance['summary'];
	  ?>
      <p><?php _e( 'Layout will be as below:', 'colormag' ) ?></p>
      <div style="text-align: center;"><img src="<?php echo get_template_directory_uri() . '/img/style-1.jpg'?>"></div>
      <p>
         <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'colormag' ); ?></label>
         <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
      </p>
      <?php _e( 'Description','colormag' ); ?>
      <textarea class="widefat" rows="5" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
      <p>
         <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e( 'Number of posts to display:', 'colormag' ); ?></label>
         <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" />
      </p>
 
      <p><input type="radio" <?php checked($type, 'latest') ?> id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>" value="latest"/><?php _e( 'Show latest Posts', 'colormag' );?><br />
       <input type="radio" <?php checked($type,'category') ?> id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>" value="category"/><?php _e( 'Show posts from a category', 'colormag' );?><br /></p>
 
      <p>
         <label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Select category', 'colormag' ); ?>:</label>
         <?php wp_dropdown_categories( array( 'show_option_none' =>' ','name' => $this->get_field_name( 'category' ), 'selected' => $category ) ); ?>
      </p>
	  <p>
         <label for="<?php echo $this->get_field_id('cat'); ?>"><?php _e( 'categories to display:', 'colormag' ); ?></label>
         <input id="<?php echo $this->get_field_id('cat'); ?>" name="<?php echo $this->get_field_name('cat'); ?>" type="text" value="<?php echo $cat; ?>" size="30" />
      </p>
	  <p>
         <label for="<?php echo $this->get_field_id('summary'); ?>"><?php _e( 'L&auml;nge des Textauszugs:', 'colormag' ); ?></label>
         <input id="<?php echo $this->get_field_id('summary'); ?>" name="<?php echo $this->get_field_name('summary'); ?>" type="text" value="<?php echo $summary; ?>" size="5" />
      </p>
		    <?php
   }
 
   function update( $new_instance, $old_instance ) {
      $instance = $old_instance;
      $instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
      if ( current_user_can('unfiltered_html') )
         $instance['text'] =  $new_instance['text'];
      else
         $instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) );
      $instance[ 'number' ] = absint( $new_instance[ 'number' ] );
      $instance[ 'type' ] = $new_instance[ 'type' ];
      $instance[ 'category' ] = $new_instance[ 'category' ];
	  $instance[ 'cat' ] = absint( $new_instance[ 'cat' ]);
	  $instance[ 'summary' ] = absint( $new_instance[ 'summary' ]);
	  
 
      return $instance;
   }
 
   function widget( $args, $instance ) {
      extract( $args );
      extract( $instance );
 
      global $post;
      $title = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : '';
      $text = isset( $instance[ 'text' ] ) ? $instance[ 'text' ] : '';
      $number = empty( $instance[ 'number' ] ) ? 4 : $instance[ 'number' ];
      $link = empty($instance['link']) ? '' : $instance['link'];
      $type = isset( $instance[ 'type' ] ) ? $instance[ 'type' ] : 'latest' ;
      $category = isset( $instance[ 'category' ] ) ? $instance[ 'category' ] : '';
	  $cat = empty( $instance[ 'cat' ] ) ? '' : $instance[ 'cat' ] ;
	  $summary = empty( $instance[ 'summary' ] ) ? '' : $instance[ 'summary' ] ;
	  if( $type == 'latest' ) {
         $get_featured_posts = new WP_Query( array(
            'posts_per_page'        => $number,
            'post_type'             => 'post',
            'ignore_sticky_posts'   => true,
		) );
      }
      else {
         $get_featured_posts = new WP_Query( array(
            'posts_per_page'        => $number,
            'post_type'             => 'post',
            'category__in'          => $category,
		) );
      }
 
	  if ($link) {
	        $before_title = $before_title . '<a href="' . esc_url($link) . '" class="widget-title-link">';
	        $after_title = '</a>' . $after_title;
        } elseif ($category) {
        	$cat_url = get_category_link($category);
	        $before_title = $before_title . '<a href="' . esc_url($cat_url) . '" class="widget-title-link">';
	        $after_title = '</a>' . $after_title;
        }
 
	  if ($cat) {
	    	$get_featured_posts = new WP_Query( array(
			'posts_per_page' 		=> $number,
			'cat'					=> $cat,
			'summary'				=> $summary,
			$type					=> 'latest',
			) );
        }
		
		
      echo $before_widget;
      ?>
      <?php
         if ( $type != 'latest' ) {
            $border_color = 'style="border-bottom-color:' . colormag_category_color($category) . ';"';
            $title_color = 'style="background-color:' . colormag_category_color($category) . ';"';
         } else {
            $border_color = '';
            $title_color = '';
         }
         if ( !empty( $title ) ) { echo '<h3 class="widget-title" '. $border_color .'><span ' . $title_color .'>'. esc_html( $title ) .'</span></h3>'; }
         if( !empty( $text ) ) { ?> <p> <?php echo esc_textarea( $text ); ?> </p> <?php } ?>
         <?php
         $i=1;
         while( $get_featured_posts->have_posts() ):$get_featured_posts->the_post();
            ?>
            <?php if( $i == 1 ) { $featured = 'colormag-featured-post-medium'; } else { $featured = 'colormag-featured-post-small'; } ?>
            <?php if( $i == 1 ) { echo '<div class="first-post">'; } elseif ( $i == 2 ) { echo '<div class="following-post">'; } ?>
               <div class="single-article clearfix">
                  <?php
                  if( has_post_thumbnail() ) {
                     $image = '';
                     $title_attribute = get_the_title( $post->ID );
                     $image .= '<figure>';
                     $image .= '<a href="' . get_permalink() . '" title="'.the_title( '', '', false ).'">';
                     $image .= get_the_post_thumbnail( $post->ID, $featured, array( 'title' => esc_attr( $title_attribute ), 'alt' => esc_attr( $title_attribute ) ) ).'</a>';
                     $image .= '</figure>';
                     echo $image;
                  }
                  ?>
                  <div class="article-content">
                     <?php colormag_colored_category(); ?>
                     <h3 class="entry-title">
                        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>"><?php the_title(); ?></a>
                     </h3>
                     <div class="below-entry-meta">
                        <?php
                           $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
                           $time_string = sprintf( $time_string,
                              esc_attr( get_the_date( 'c' ) ),
                              esc_html( get_the_date() )
                           );
                           printf( __( '<span class="posted-on"><a href="%1$s" title="%2$s" rel="bookmark"><i class="fa fa-calendar-o"></i> %3$s</a></span>', 'colormag' ),
                              esc_url( get_permalink() ),
                              esc_attr( get_the_time() ),
                              $time_string
                           );
                        ?>
                        <span class="byline"><span class="author vcard"><i class="fa fa-user"></i><a class="url fn n" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" title="<?php echo get_the_author(); ?>"><?php echo esc_html( get_the_author() ); ?></a></span></span>
                        <span class="comments"><i class="fa fa-comment"></i><?php comments_popup_link( '0', '1', '%' );?></span>
                     </div>
                     <?php if( $i == 1 ) { ?>
                     <div class="entry-content">
                        <?php excerpt_chars($summary); ?>
                     </div>
                     <?php } ?>
                  </div>

               </div>
            <?php if( $i == 1 ) { echo '</div>'; } ?>
         <?php
            $i++;
         endwhile;
         if ( $i > 2 ) { echo '</div>'; }
         // Reset Post Data
         wp_reset_query();
         ?>
      <!-- </div> -->
      <?php echo $after_widget;
      }
}
