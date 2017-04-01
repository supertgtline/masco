<div id="quickCategories">

	<div class="navTabs">
		<ul class="tabs">
			<?php
			$i = 0;
			$c = 10;
			
			while ($i < $c)
			{
				$i++;
				$category = option::get('featured_category_'.$i);
				
				if ($category != 0)
				{
					$cat = get_category($category,false);
					echo'<li><a href="#tab'.$i.'">'.$cat->name.'</a></li>';
				}
			}          
			?>
		</ul><!-- end .tabs -->
	</div><!-- end .navTabs -->
	
	<div class="box">
	
		<div class="tab_container">
		
			<?php
			$cc = 0;
			$c = 10;
			
			$posts_num = option::get('featured_categories_posts');
			
			while ($cc < $c)
			{
			
				$cc++;
				$category = option::get('featured_category_'.$cc);
				
				if ($category != 0)
				{
				
					$cat = get_category($$category,false);
					$catlink = get_category_link($$category);
					
					$loop = new WP_Query( array( 'post__not_in' => get_option( 'sticky_posts' ), 'posts_per_page' => $posts_num, 'orderby' => 'date', 'order' => 'DESC', 'cat' => $category ) );
					?>
					
					<div id="tab<?php echo $cc; ?>" class="tab_content">
					<?php if ( $loop->have_posts() ) : ?>
					<ul class="posts">
						<?php 
						$x = 0;
						while ($loop->have_posts()) : $loop->the_post(); update_post_caches($posts); $x++;
						?>
						<li<?php if ($x == 6) {echo ' class="last"';} ?>>

							<?php
							get_the_image( array( 'size' => 'featured-categories', 'width' => 140, 'height' => 90, 'before' => '<div class="cover">', 'after' => '</div>' ) );
							?>

							<h2><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>

						</li><?php endwhile; ?>

					</ul><?php endif; ?>
					
					</div><!-- end .tab_content -->
				
				<?php } // if category is set 
			} // endwhile ?>
		
		</div><!-- end .tab_container -->
		
		<div class="cleaner">&nbsp;</div>
	
	</div><!-- end .box -->

</div><!-- end #quickCategories -->
<?php wp_reset_query(); ?>