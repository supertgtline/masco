<?php

/*------------------------------------------*/
/* WPZOOM: Featured Categories		*/
/*------------------------------------------*/

class wpzoom_widget_feat_category extends WP_Widget {

	/* Widget setup. */
	function wpzoom_widget_feat_category() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'wpzoom', 'description' => __('Custom WPZOOM widget. Displays posts from two categories in 2 columns. Insert only into Homepage: Content Widgets sidebar.', 'wpzoom') );
		
		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'wpzoom-widget-feat-category' );
		
		/* Create the widget. */
		$this->WP_Widget( 'wpzoom-widget-feat-category', __('WPZOOM: Featured Categories', 'wpzoom'), $widget_ops, $control_ops );
	}
	
	/* How to display the widget on the screen. */
	function widget( $args, $instance ) {
	
		extract( $args );
		
		/* Our variables from the widget settings. */

		$category1 = get_category($instance['category1']);
		if ($category1) {
			$categoryLink1 = get_category_link($category1);
		}
		$category2 = get_category($instance['category2']);
		if ($category2) {
			$categoryLink2 = get_category_link($category2);
		}

	
		$title = apply_filters('widget_title', $instance['title'] );
		$showCategory = $instance['category_title'];
		$showPostCategory = $instance['post_category'];
		$showImageOnly = $instance['image_only'];		
	
		?>
		<div class="featCategories">
		<?php	
	
		$z = 0;
		while ($z < 2)
		{
		$z++;
		
		$current_category = "category$z";

		$category = get_category($instance[$current_category]);
		if ($category) {
			$category_link = get_category_link($category);
		}

		?>

		<div class="category<?php if ($z == 2) { echo ' category-last';} ?>">
		
			<div class="title">
				<a href="<?php echo $category_link; ?>"><img src="<?php bloginfo('template_url'); ?>/images/icon_rss.png" alt="" /></a>
				<h3><a href="<?php echo"$category_link";?>"><?php echo"$category->name";?></a></h3>
			</div><!-- end .title -->
			
			<div class="box">
              
			<?php 
			$i = 0;
			
			$loop = new WP_Query( array( 'posts_per_page' => $instance['posts'], 'orderby' => 'date', 'order' => 'DESC', 'cat' => $category->cat_ID ) );
			?>

			<?php if ( $loop->have_posts() ) : ?>

	            <ul class="posts">

				<?php    
				$x = 0;
				while ( $loop->have_posts() ) : $loop->the_post(); global $post;

				$x++;
				if ($x == 1)
				{ 

					unset($prev, $videocode);		
			
					if ($showImageOnly != 'on') {
			
						$videocode = get_post_meta($post->ID, 'wpzoom_post_embed_code', true);
						          
						if (strlen($videocode) > 10){
							$videocode = preg_replace("/(width\s*=\s*[\"\'])[0-9]+([\"\'])/i", "$1 290 $2", $videocode);
							$videocode = preg_replace("/(height\s*=\s*[\"\'])[0-9]+([\"\'])/i", "$1 160 $2", $videocode);
							$videocode = str_replace("<embed","<param name='wmode' value='transparent'></param><embed",$videocode);
							$videocode = str_replace("<embed","<embed wmode='transparent' ",$videocode);
						} // if strlen of video > 10
					} // if videos are allowed by widget settings

				?>

				<li class="first">
				
					<?php if ($videocode) { ?>
					<div class="cover">
						<?php echo $videocode; ?>
					</div><!-- end .cover -->
					<?php } else {

					get_the_image( array( 'size' => 'featured-category-widget', 'width' => 280, 'height' => 160, 'before' => '<div class="cover">', 'after' => '</div>' ) );

					} ?>
					
					<h2><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
					<p><?php the_content_limit(140, ''); ?></p>

				</li>
				<?php } // if $x == 1
				else { ?>
				<li>
					<h2><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
				</li><?php } // else ?>
				<?php endwhile; //  ?>
			
				</ul>
				<?php else : ?>
  
				<p class="title"><?php _e('There are no posts in this category', 'wpzoom');?></p>
  
  				<?php endif; ?>
              
				<div class="cleaner">&nbsp;</div>
			
			</div><!-- end .box -->
			
			<div class="cleaner">&nbsp;</div>
			
		</div><!-- end .category -->
		<?php 
		} // while $z < 2
		?>
		
		</div>
		<div class="cleaner">&nbsp;</div>
		

		<?php 
		}
	
		/* Update the widget settings.*/
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
	
			/* Strip tags for title and name to remove HTML (important for text inputs). */
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['category1'] = $new_instance['category1'];
			$instance['category2'] = $new_instance['category2'];
			
			$instance['posts'] = $new_instance['posts'];
			$instance['image_only'] = $new_instance['image_only'];
	 
			return $instance;
		}
	
		/** Displays the widget settings controls on the widget panel.
		 * Make use of the get_field_id() and get_field_name() function when creating your form elements. This handles the confusing stuff. */
		function form( $instance ) {
	
			/* Set up some default widget settings. */
			$defaults = array('title' => 'Widget Title','category1' => '0','category2' => '0','posts'=>'4');
			$instance = wp_parse_args( (array) $instance, $defaults );
	    ?>
	
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>">Widget Title:</label><br />
				<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:90%;" />
			</p>

			<p><strong>Display Options:</strong></p>

			<p>
				<?php _e('Category 1:', 'wpzoom'); ?>
				<select id="<?php echo $this->get_field_id('category1'); ?>" name="<?php echo $this->get_field_name('category1'); ?>" style="width:90%;">
					<option value="0">Choose category:</option>
					<?php
					$cats = get_categories('hide_empty=0');
					
					foreach ($cats as $cat) {
					$option = '<option value="'.$cat->term_id;
					if ($cat->term_id == $instance['category1']) { $option .='" selected="selected';}
					$option .= '">';
					$option .= $cat->cat_name;
					$option .= ' ('.$cat->category_count.')';
					$option .= '</option>';
					echo $option;
					}
				?>
				</select>
			</p>

			<p>
				<?php _e('Category 2:', 'wpzoom'); ?>
				<select id="<?php echo $this->get_field_id('category2'); ?>" name="<?php echo $this->get_field_name('category2'); ?>" style="width:90%;">
					<option value="0">Choose category:</option>
					<?php
					$cats = get_categories('hide_empty=0');
					
					foreach ($cats as $cat) {
					$option = '<option value="'.$cat->term_id;
					if ($cat->term_id == $instance['category2']) { $option .='" selected="selected';}
					$option .= '">';
					$option .= $cat->cat_name;
					$option .= ' ('.$cat->category_count.')';
					$option .= '</option>';
					echo $option;
					}
				?>
				</select>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('posts'); ?>"><?php _e('Posts to display:', 'wpzoom'); ?></label>
				<select id="<?php echo $this->get_field_id('posts'); ?>" name="<?php echo $this->get_field_name('posts'); ?>" style="width:90%;">
				<?php
					$m = 0;
					while ($m < 11) {
						$m++;
						$option = '<option value="'.$m;
						if ($m == $instance['posts']) { $option .='" selected="selected';}
						$option .= '">';
						$option .= $m;
						$option .= '</option>';
						echo $option;
					}
				?>
				</select>
			</p>

			<hr style="height: 1px; line-height: 1px; font-size: 1px; border: none; border-top: solid 1px #aaa; margin: 10px 0;" />
			
		<p>
			<input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id('image_only'); ?>" name="<?php echo $this->get_field_name('image_only'); ?>" <?php if ($instance['image_only'] == 'on') { echo ' checked="checked"';  } ?> /> 
			<label for="<?php echo $this->get_field_id('image_only'); ?>"><?php _e('Display only images (no videos)', 'wpzoom'); ?></label>
			<br/>
		</p>
		
		<hr style="height: 1px; line-height: 1px; font-size: 1px; border: none; border-top: solid 1px #aaa; margin: 10px 0;" />
		
		<?php
		}
}

function wpzoom_register_fc_widget() {
	register_widget('wpzoom_widget_feat_category');
}
add_action('widgets_init', 'wpzoom_register_fc_widget');
?>