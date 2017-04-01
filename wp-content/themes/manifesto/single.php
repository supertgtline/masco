<?php get_header(); ?>

<?php
// post custom fields 
$template = get_post_meta($post->ID, 'wpzoom_post_template', true);
$showauthor = get_post_meta($post->ID, 'wpzoom_post_author', true); 
?>


<div id="frame">
	<div id="content"<?php 
	if ($template == 'side-left') {echo' class="side-left"';}
	if ($template == 'full') {echo' class="full-width"';} 
	?>>
		
		<div class="wrapper">
		
			<div id="main">
			
				<?php wp_reset_query(); if (have_posts()) : while (have_posts()) : the_post(); ?>
				
				<div id="single">
					
					<div class="title breadcrumbs">
						<?php echo '<h3>'; wpzoom_breadcrumbs(); echo'</h3>'; ?>
					</div><!-- end .title -->
					
					<div class="box box-single">
					
						<?php if (option::get('banner_post_top_enable') == 'on') { ?>
						<div class="banner">
								
							<?php if ( option::get('banner_post_top_html') <> "") { 
								echo stripslashes(option::get('banner_post_top_html'));             
							} else { ?>
								<a href="<?php echo option::get('banner_post_top_url'); ?>" rel="nofollow" title="<?php echo option::get('banner_post_top_alt'); ?>"><img src="<?php echo option::get('banner_post_top'); ?>" alt="<?php echo option::get('banner_post_top_alt'); ?>" /></a>
							<?php } ?>		   	
										
						</div><!-- end .banner -->
				
						<div class="cleaner">&nbsp;</div>
						<?php } ?>
						
						<h1 class="title"><?php the_title(); ?></h1>
						
						<div class="postmetadata">
						
							<ul>
								<?php if (option::get('post_date') == 'on') { ?><li class="calendar"><time datetime="<?php the_time("Y-m-d"); ?>" pubdate><?php the_time("j F Y"); ?></time></li><?php } ?>
								<?php if (option::get('post_author') == 'on') { ?><li class="author"><?php _e('By', 'wpzoom');?> <?php the_author_posts_link(); ?></li><?php } ?>
								<?php if (option::get('post_category') == 'on') { ?><li class="category"><?php the_category(', '); ?></li><?php } ?>
								<?php if (option::get('post_comments') == 'on') { ?><li class="comments"><?php comments_popup_link( __('0 comments', 'wpzoom'), __('1 comment', 'wpzoom'), __('% comments', 'wpzoom'), '', ''); ?></li><?php } ?>
								<?php edit_post_link( __('Edit this post &raquo;', 'wpzoom'), '<li>', '</li>'); ?>
							</ul>
							<div class="cleaner">&nbsp;</div>
						
						</div><!-- end .postmetadata -->
						
						<div class="postcontent">
						
							<?php the_content(); ?>
							<div class="cleaner">&nbsp;</div>
							<?php wp_link_pages(array('before' => '<p class="pages"><strong>'.__('Pages', 'wpzoom').':</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
							<?php if (option::get('post_tags') == 'on') { the_tags( '<p class="tags"><strong>'.__('Tags', 'wpzoom').':</strong> ', ', ', '</p>'); } ?>
						
						</div><!-- end .postcontent -->
						
						<?php if (option::get('post_share') == 'on') { ?>
						<div class="sep">&nbsp;</div>
						<div class="sharing">
							<span class="share_btn"><a href="http://twitter.com/share" data-url="<?php the_permalink() ?>" class="twitter-share-button" data-count="horizontal">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></span>
							<span class="share_btn"><g:plusone size="medium"></g:plusone></span>
							<span class="share_btn"><iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo urlencode(get_permalink($post->ID)); ?>&amp;layout=button_count&amp;show_faces=false&amp;width=80&amp;action=like&amp;font=arial&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:80px; height:21px;" allowTransparency="true"></iframe></span>
							<div class="cleaner">&nbsp;</div>
						</div><!-- end .divider .social -->
						<?php } ?>

						<div class="cleaner">&nbsp;</div>
						
						<?php if (option::get('banner_post_bottom_enable') == 'on') { ?>
						<div class="banner">
								
							<?php if ( option::get('banner_post_bottom_html') <> "") { 
								echo stripslashes(option::get('banner_post_bottom_html'));             
							} else { ?>
								<a href="<?php echo option::get('banner_post_bottom_url'); ?>" rel="nofollow" title="<?php echo option::get('banner_post_bottom_alt'); ?>"><img src="<?php echo option::get('banner_post_bottom'); ?>" alt="<?php echo option::get('banner_post_bottom_alt'); ?>" /></a>
							<?php } ?>		   	
										
						</div><!-- end .banner -->
						
						<?php } ?>
					
					</div><!-- end .box -->
					<?php if ($showauthor == 'Yes') { ?>
					<div class="box box-author">
					
						<div class="postcontent">
							<h2 class="title"><?php _e('About the author', 'wpzoom');?></h2>
							<a href=""><?php echo get_avatar( get_the_author_id() , 70 ); ?></a>
							<p><?php the_author_description(); ?></p>
							<p class="more"><?php _e('More posts by', 'wpzoom');?> <?php the_author_posts_link(); ?><?php if (get_the_author_meta('user_url')) { ?> | <a href="<?php the_author_meta('user_url'); ?>"><?php _e('Visit the site of', 'wpzoom');?> <?php the_author_meta('display_name'); ?></a><?php } ?></p> 
						</div><!-- end .postcontent -->

						<div class="cleaner">&nbsp;</div>
					</div><!-- end .box -->
					<?php } // if author information should be shown ?>
					
					<?php if (option::get('post_comments') == 'on') { ?>
					
						<?php comments_template(); ?>
					
					<?php } ?>
				
				</div><!-- end #single -->
				
				<div class="cleaner">&nbsp;</div>
				
				<?php endwhile; else: ?>
				
				<p><?php _e('Sorry, no posts matched your criteria', 'wpzoom');?>.</p>
				<?php endif; ?>
			
			</div><!-- end #main -->
			
			<?php if ($template != 'full') { ?>
			<div id="sidebar">
			
			<?php get_sidebar(); ?>
			
			</div><!-- end #sidebar -->
			<?php } //if template is not full width  ?>
			
			<div class="cleaner">&nbsp;</div>
		
		</div><!-- end .wrapper -->
	</div><!-- end #content -->
</div><!-- end #frame -->

<?php get_footer(); ?>