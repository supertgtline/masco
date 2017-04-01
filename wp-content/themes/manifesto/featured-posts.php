<?php
$loop = new WP_Query( 
array( 
	'post__not_in' => get_option( 'sticky_posts' ),
	'posts_per_page' => option::get('featured_number'),
	'meta_key' => 'wpzoom_is_featured',
	'meta_value' => 1				
) );
?>

<div id="featPosts">

	<div id="postsBig">
		<div class="box">
			<div class="container">
			
				<?php 
				$i = 0;
				if ( $loop->have_posts() ) : ?>
				
				<ul class="posts slides">

				<?php while ( $loop->have_posts() ) : $loop->the_post(); $m++; ?>

					<li class="slide" id="post-<?php the_ID(); ?>">
						<?php
						unset($cropLocation, $image);
						
						$videocode = get_post_meta($post->ID, 'wpzoom_post_embed_code', true); // get embed code

						if (strlen($videocode) > 1) {
							$videocode = preg_replace("/(width\s*=\s*[\"\'])[0-9]+([\"\'])/i", "$1 610 $2", $videocode);
							$videocode = preg_replace("/(height\s*=\s*[\"\'])[0-9]+([\"\'])/i", "$1 300 $2", $videocode);
							$videocode = str_replace("<embed","<param name='wmode' value='transparent'></param><embed",$videocode);
							$videocode = str_replace("<embed","<embed wmode='transparent' ",$videocode);
							?><div class="cover"><?php echo "$videocode"; ?></div>
							<?php
						} // if video is set
						else { 

							get_the_image( array( 'size' => 'homepage-slider', 'width' => 600, 'height' => 300, 'before' => '<div class="cover">', 'after' => '</div>' ) );
 
						} // if no video ?>
						
						<div class="postcontent">
							<h2><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
							<div class="postmetadata">
								<ul>
									<li class="calendar"><?php the_time("$dateformat"); ?></li>
									<li class="author"><?php _e('By', 'wpzoom');?> <?php the_author_posts_link(); ?></li>
									<li class="category"><?php the_category(', '); ?></li>
									<li class="comments"><a href="<?php the_permalink() ?>#commentspost" title="Jump to the comments"><?php comments_number(__('no comments', 'wpzoom'),__('1 comment', 'wpzoom'),__('% comments', 'wpzoom')); ?></a></li>
								</ul>
							</div><!-- end .postmetadata -->
							<?php the_excerpt(); ?>
						</div><!-- end .postcontent -->
						<div class="cleaner">&nbsp;</div>

					</li><!-- end #post-<?php the_ID(); ?> --><?php endwhile; ?>

				</ul><?php endif; ?>

				<div class="cleaner">&nbsp;</div>
			</div><!-- end .container -->
		</div><!-- end .box -->
	</div><!-- end #postsBig -->
	
	<div id="postsSmall">
		<div class="box box-nopadd">
		
			<?php 
			rewind_posts();
			$i = 0;
			if ( $loop->have_posts() ) : ?>
			<ul class="posts pagination">
				<?php while ( $loop->have_posts() ) : $loop->the_post(); $m++; ?>
				<li id="post-thumb-<?php the_ID(); ?>"><a href="#" rel="nofollow">

				<?php
				get_the_image( array( 'size' => 'homepage-slider-thumb', 'width' => 45, 'height' => 30, 'before' => '<div class="cover">', 'after' => '</div>', 'link_to_post' => false ) ); 
				?>

				<div class="postcontent">
					<h2><?php the_title(); ?></h2>
					<p><?php the_content_limit('80'); ?></p>
				</div><!-- end .postcontent -->

				<div class="cleaner">&nbsp;</div>

				</a></li><!-- end #post-thumb-<?php the_ID(); ?> --><?php endwhile; ?>
				<div class="cleaner">&nbsp;</div>
			</ul><?php endif; ?>
			<div class="cleaner">&nbsp;</div>

		</div><!-- end .box -->
	</div><!-- end #postsSmall -->
	
	<div class="cleaner">&nbsp;</div>

</div><!-- end #featPosts -->
<?php wp_reset_query(); ?>

<script type="text/javascript" charset="utf-8">
jQuery(document).ready(
	function($)
	{
	$('#featPosts').loopedSlider({
		autoHeight: true,
		containerClick: false,
		<?php if (option::get('featured_autoplay') > 0) { ?>autoStart: <?php echo option::get('featured_autoplay') . ','; } ?>
		slidespeed: 500
	});
});
</script>