<?php $mts_options = get_option(newstimes); ?>
<?php
	// default = 3
	$top_footer_num = (!empty($mts_options['mts_top_footer_num']) && $mts_options['mts_top_footer_num'] == 4) ? 4 : 3;
	$bottom_footer_num = (!empty($mts_options['mts_bottom_footer_num']) && $mts_options['mts_bottom_footer_num'] == 4) ? 4 : 3;
?>
		</div><!--#page-->
	</div><!--.main-container-->
	<footer>
		<?php
		if ( is_single() && 'post' === get_post_type() ) {
			$show_carousel = isset($mts_options['mts_footer_carousel_location']['single']) == '1';
		} elseif ( is_home() ) {
			$show_carousel = isset($mts_options['mts_footer_carousel_location']['home']) == '1';
		} else {
			$show_carousel = isset($mts_options['mts_footer_carousel_location']['other']) == '1';
		}

		if ( $mts_options['mts_footer_carousel'] && $show_carousel ) {

			if ( empty($mts_options['mts_footer_carousel_cat']) || !is_array($mts_options['mts_footer_carousel_cat']) ) {
				$mts_options['mts_footer_carousel_cat'] = array('0');
			}
			$carousel_cat = implode(",", $mts_options['mts_footer_carousel_cat']);

		$car_query = new WP_Query('cat='.$carousel_cat.'&posts_per_page=-1');

		$count = 0; if ( $car_query->have_posts() ) :
		?>
		<div class="footer-carousel-wrap">
			<div class="container">
				<div class="slider-container">
					<div class="flex-container loading">
						<div id="footer-post-carousel" class="flexslider">
							<ul class="slides">
								<?php while ( $car_query->have_posts() ) : $car_query->the_post(); ?>
								<li class="<?php if($count === 0) echo ' show-post-data';?>">
									<div class="dark-style post-box">
										<div class="post-data">
											<header>
												<a href="<?php the_permalink(); ?>" class="title post-title" title="<?php the_title(); ?>"><?php the_title(); ?></a>
												<?php if($mts_options['mts_home_headline_meta'] == '1') { ?>
													<div class="post-info">
														<?php if(isset($mts_options['mts_home_headline_meta_info']['date']) == '1') { ?>
															<span class="thetime updated"><?php the_time( get_option( 'date_format' ) ); ?></span>
														<?php } ?>
													</div>
												<?php } ?>
											</header>
										</div>
									</div>
									<a href="<?php the_permalink(); ?>">
										<?php the_post_thumbnail('related',array('title' => '')); ?>
									</a>
								</li>
								<?php $count++; endwhile; ?>
							</ul>
						</div>
					</div>
				</div><!-- slider-container -->
			</div>
		</div>
		<?php endif; wp_reset_postdata(); }?>
		
			<div class="container">
			<?php if ($mts_options['mts_top_footer']) : ?>
				<div class="footer-widgets top-footer-widgets widgets-num-<?php echo $top_footer_num; ?>">
					<div class="f-widget f-widget-1">
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-top') ) : ?><?php endif; ?>
					</div>
					<div class="f-widget f-widget-2">
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-top-2') ) : ?><?php endif; ?>
					</div>
					<div class="f-widget f-widget-3 <?php echo ($top_footer_num == 3) ? 'last' : ''; ?>">
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-top-3') ) : ?><?php endif; ?>
					</div>
					<?php if ($top_footer_num == 4) : ?>
					<div class="f-widget f-widget-4 last">
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-top-4') ) : ?><?php endif; ?>
					</div>
					<?php endif; ?>
				</div><!--.top-footer-widgets-->
			<?php endif; ?>
			</div>
			<?php if ($mts_options['mts_bottom_footer']) : ?>
				<div class="footer-widgets bottom-footer-widgets widgets-num-<?php echo $bottom_footer_num;?>">
					<div class="container">
					<div class="f-widget f-widget-1">
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-bottom') ) : ?><?php endif; ?>
					</div>
					<div class="f-widget f-widget-2">
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-bottom-2') ) : ?><?php endif; ?>
					</div>
					<div class="f-widget f-widget-3 <?php echo ($bottom_footer_num == 3) ? 'last' : ''; ?>">
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-bottom-3') ) : ?><?php endif; ?>
					</div>
					<?php if ($bottom_footer_num == 4) : ?>
					<div class="f-widget f-widget-4 last">
						<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-bottom-4') ) : ?><?php endif; ?>
					</div>
					<?php endif; ?>
					</div><!--.container-->
				</div><!--.bottom-footer-widgets-->
			<?php endif; ?>
			
		<div class="copyrights">
			<div class="container">
				<?php mts_copyrights_credit(); ?>
			</div><!--.container-->
		</div><!--.copyrights-->
	</footer><!--footer-->
</div><!--.main-container-wrap-->
<?php mts_footer(); ?>
<?php wp_footer(); ?>
</body>
</html>