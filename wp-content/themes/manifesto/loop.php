<?php wp_reset_query(); ?>
<div id="archive">

	<?php if (is_category()) { ?>
	
	<div class="title breadcrumbs">
		<?php $cat_ID = get_query_var('cat'); ?>
		<a href="<?php echo get_category_feed_link($cat_ID, '' ); ?>"><img src="<?php bloginfo('template_directory'); ?>/images/icon_rss.png" alt="" /></a>
		<?php echo '<h3>'; wpzoom_breadcrumbs(); echo'</h3>'; ?>
	</div><!-- end .title -->
	
	<?php } 
	elseif (!is_category() && !is_home()) { ?>
	
	<div class="title breadcrumbs">
		<?php echo '<h3>'; wpzoom_breadcrumbs(); echo'</h3>'; ?>
	</div><!-- end .title -->
	
	<?php }
	else { ?>
		<div class="title"><h3><?php _e('Recent Posts', 'wpzoom');?></h3></div><!-- end .title -->
	<?php } ?>
	
	<div class="box">
	
		<?php if (have_posts()) : ?>

		<ul class="posts">

			<?php while (have_posts()) : the_post(); $i++; ?>
			<li id="post-<?php the_ID(); ?>">

				<?php
				get_the_image( array( 'size' => 'loop-main', 'width' => 160, 'height' => 120, 'before' => '<div class="cover">', 'after' => '</div>' ) );
				?>
				
				<div class="postcontent">
					<h2><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
					<div class="postmetadata">
						<ul>
							<?php if (option::get('display_date') == 'on') { ?><li class="calendar"><time datetime="<?php the_time("Y-m-d"); ?>" pubdate><?php the_time("j F Y"); ?></time></li><?php } ?>
							<?php if (option::get('display_author') == 'on') { ?><li class="author"><?php _e('By', 'wpzoom');?> <?php the_author_posts_link(); ?></li><?php } ?>
							<?php if (option::get('display_category') == 'on') { ?><li class="category"><?php the_category(', '); ?></li><?php } ?>
							<?php if (option::get('display_comments') == 'on') { ?><li class="comments"><?php comments_popup_link( __('0 comments', 'wpzoom'), __('1 comment', 'wpzoom'), __('% comments', 'wpzoom'), '', ''); ?></li><?php } ?>
						</ul>
					</div><!-- end .postmetadata -->
					<?php the_excerpt(); ?>
					<p class="more"><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>" class="readmore" rel="nofollow"><?php _e('continue reading &raquo;', 'wpzoom');?></a> <?php edit_post_link( __('Edit this post', 'wpzoom'), ' | ', ''); ?></p>
				</div><!-- end .postcontent -->

				<div class="cleaner">&nbsp;</div>
				<div class="sep">&nbsp;</div>
			</li><!-- end #post-<?php the_ID(); ?> -->
			<?php endwhile; //  ?>
		</ul>

		<div class="cleaner">&nbsp;</div>
		<?php else : ?>
		
		<p class="title"><?php _e('There are no posts in this category', 'wpzoom');?></p>
		
		<?php endif; ?>
		
		<?php get_template_part( 'pagination'); ?>
	
	</div><!-- end .box -->

</div><!-- end #archive -->

<div class="cleaner">&nbsp;</div>