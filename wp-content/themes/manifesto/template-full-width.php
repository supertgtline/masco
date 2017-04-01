<?php
/*
Template Name: Full Width
*/
?>

<?php get_header(); ?>

<div id="frame">
	<div id="content" class="full-width">
	
		<div class="wrapper">
		
			<div id="main">
			
				<?php wp_reset_query(); if (have_posts()) : while (have_posts()) : the_post(); ?>
				
				<div id="single">
				
					<div class="title breadcrumbs">
						<?php echo '<h3>'; wpzoom_breadcrumbs(); echo'</h3>'; ?>
					</div><!-- end .title -->
					
					<div class="box box-single">
					
						<h1 class="title page"><?php the_title(); ?></h1>
						
						<?php edit_post_link( __('Edit this page &raquo;', 'wpzoom'), '', ''); ?>
						
						<div class="postcontent">
						
							<?php the_content(); ?>
							<div class="cleaner">&nbsp;</div>
							<?php wp_link_pages(array('before' => '<p class="pages"><strong>'.__('Pages', 'wpzoom').':</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
							<?php edit_post_link( __('Edit this page &raquo;', 'wpzoom'), '', ''); ?>
						
						</div><!-- end .postcontent -->

						<?php if (option::get('page_share') == 'on') { ?>
						<div class="sep">&nbsp;</div>
						<div class="sharing">
							<span class="share_btn"><a href="http://twitter.com/share" data-url="<?php the_permalink() ?>" class="twitter-share-button" data-count="horizontal">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></span>
							<span class="share_btn"><g:plusone size="medium"></g:plusone></span>
							<span class="share_btn"><iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo urlencode(get_permalink($post->ID)); ?>&amp;layout=button_count&amp;show_faces=false&amp;width=80&amp;action=like&amp;font=arial&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:80px; height:21px;" allowTransparency="true"></iframe></span>
							<div class="cleaner">&nbsp;</div>
						</div><!-- end .divider .social -->
						<?php } ?>
						
						<div class="cleaner">&nbsp;</div>
					
					</div><!-- end .box -->
					
					<?php if (option::get('page_comments') == 'on') { ?>
					
					<?php comments_template(); ?>
					
					<?php } ?>
				
				</div><!-- end #single -->
				
				<div class="cleaner">&nbsp;</div>
				
				<?php endwhile; else: ?>
				
				<p><?php _e('Sorry, no posts matched your criteria', 'wpzoom');?>.</p>
				<?php endif; ?>
			
			</div><!-- end #main -->
			
			<div class="cleaner">&nbsp;</div>
		</div><!-- end .wrapper -->
	</div><!-- end #content -->
</div><!-- end #frame -->

<?php get_footer(); ?>