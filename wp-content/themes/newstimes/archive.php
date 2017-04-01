<?php
$mts_options = get_option(newstimes);
?>
<?php get_header(); ?>
<div id="page">
	<div class="article">
		<div id="content_box">
			<h1 class="postsby">
				<?php if (is_category()) { ?>
					<span><?php single_cat_title(); ?><?php _e(" Archive", "mythemeshop"); ?></span>
				<?php } elseif (is_tag()) { ?> 
					<span><?php single_tag_title(); ?><?php _e(" Archive", "mythemeshop"); ?></span>
				<?php } elseif (is_author()) { ?>
					<span><?php  $curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author)); echo $curauth->nickname; _e(" Archive", "mythemeshop"); ?></span> 
				<?php } elseif (is_day()) { ?>
					<span><?php _e("Daily Archive:", "mythemeshop"); ?></span> <?php the_time('l, F j, Y'); ?>
				<?php } elseif (is_month()) { ?>
					<span><?php _e("Monthly Archive:", "mythemeshop"); ?>:</span> <?php the_time('F Y'); ?>
				<?php } elseif (is_year()) { ?>
					<span><?php _e("Yearly Archive:", "mythemeshop"); ?>:</span> <?php the_time('Y'); ?>
				<?php } ?>
			</h1>
			<section id="latest-posts" class="clearfix">
			<?php $j = 1; if (have_posts()) : while (have_posts()) : the_post(); ?>
				<article class="latestPost excerpt post-box vertical">
					<div class="post-img">
						<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="nofollow">
							<?php the_post_thumbnail('featuredfull',array('title' => '')); ?>
						</a>
					</div>
					<div class="post-data">
						<div class="post-data-container">
							<header>
								<h2 class="title post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
								<?php if($mts_options['mts_home_headline_meta'] == '1') { ?>
									<div class="post-info">
										<?php if( isset($mts_options['mts_home_headline_meta_info']['category']) == '1') { ?>
											<span class="thecategory"><?php the_category(' ') ?></span>
										<?php } ?>
										<?php if(isset($mts_options['mts_home_headline_meta_info']['date']) == '1') { ?>
											<span class="thetime updated"><?php the_time( get_option( 'date_format' ) ); ?></span>
										<?php } ?>
										<?php if(isset($mts_options['mts_home_headline_meta_info']['comment']) == '1') { ?>
											<span class="thecomment"><i class="fa fa-comments"></i> <a rel="nofollow" href="<?php comments_link(); ?>"><?php echo comments_number('0','1','%');?></a></span>
										<?php } ?>
										<?php if(isset($mts_options['mts_home_headline_meta_info']['author']) == '1') { ?>
											<span class="theauthor"><i class="fa fa-user"></i> <?php the_author_posts_link(); ?></span>
										<?php } ?>
										<?php if (function_exists('wp_review_show_total')) wp_review_show_total(true, 'review-total-only'); ?>
									</div>
								<?php } ?>
							</header>
							<div class="post-excerpt">
								<?php echo mts_excerpt(35); ?>
							</div>
							<?php mts_readmore(); ?>
						</div>
					</div>
				</article><!--.post-box-->
			<?php $j++; endwhile; endif; ?>
			<!--Start Pagination-->
			<?php if (isset($mts_options['mts_pagenavigation_type']) && $mts_options['mts_pagenavigation_type'] == '1' ) { ?>
				<?php $additional_loop = 0; mts_pagination($additional_loop['max_num_pages']); ?>
			<?php } else { ?>
				<div class="pagination pagination-previous-next">
					<ul>
						<li class="nav-previous"><?php next_posts_link( '<i class="fa fa-chevron-left"></i> '. __( 'Previous', 'mythemeshop' ) ); ?></li>
						<li class="nav-next"><?php previous_posts_link( __( 'Next', 'mythemeshop' ).' <i class="fa fa-chevron-right"></i>' ); ?></li>
					</ul>
				</div>
			<?php } ?>
			<!--End Pagination-->
			</section><!--#latest-posts-->
		</div>
	</div>
	<?php get_sidebar(); ?>
<?php get_footer(); ?>