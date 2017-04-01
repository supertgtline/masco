<?php
// Get options
$mts_options = get_option(newstimes);

// Homepage featured sections layout --------------------------------------------
$homepage_fs_layout = $mts_options['mts_homepage_fs_layout'];// 1, 2, 3

// Featured Section 1 settings ( slider is here) --------------------------------
$slider_enabled = ( $mts_options['mts_featured_slider'] === '1' ) ? true : false;

if ( empty($mts_options['mts_featured_slider_cat']) || !is_array($mts_options['mts_featured_slider_cat']) ) {
    $mts_options['mts_featured_slider_cat'] = array('0');
}
$slider_cat = implode(",", $mts_options['mts_featured_slider_cat']);

// Featured Section 2 settings --------------------------------------------------
$f1_enabled    = ( $mts_options['mts_featured_section_1'] === '1' ) ? true : false;
if ( empty($mts_options['mts_featured_section_1_cat']) || !is_array($mts_options['mts_featured_section_1_cat']) ) {
    $mts_options['mts_featured_section_1_cat'] = array('0');
}
$f1_cat        = implode(",", $mts_options['mts_featured_section_1_cat']);// post categories
$f1_title      = $mts_options['mts_featured_section_1_title'];
$f1_filter     = ( $mts_options['mts_featured_section_1_ajax_filter'] === '1' ) ? true : false;
$f1_pagination = ( $mts_options['mts_featured_section_1_ajax_pagination'] === '1' ) ? true : false;
// number of posts for first "checker dark" layout
$f1_post_num = '3';

// Featured Section 3 settings --------------------------------------------------
get_header(); ?>
<div id="page">
    <?php if ( is_home() && !is_paged() && ( $homepage_fs_layout === '1' || $homepage_fs_layout === '2' ) ) { ?>
        <?php if ( $slider_enabled ) { ?>
        <section id="featured-section-1" class="featured-section featured-section-1-1 clearfix">
            <div class="slider-container">
                <div class="flex-container loading">
                    <div id="slider" class="flexslider slider1 fs-slider1">
                        <ul class="slides">
                            <?php
                            $slider_thumb = 'slider1';

                            $my_query = new WP_Query('cat='.$slider_cat.'&posts_per_page='.$mts_options['mts_featured_slider_num']);
                            while ( $my_query->have_posts() ) : $my_query->the_post();
                                $image_id = get_post_thumbnail_id();
                                $image_url = wp_get_attachment_image_src($image_id,'related');
                                $image_url = $image_url[0];
                            ?>
                            <li data-thumb="<?php echo $image_url; ?>">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail($slider_thumb,array('title' => '')); ?>
                                </a>
                                <div class="flex-caption dark-style vertical-small post-box">
                                    <div class="post-data">
                                        <div class="post-data-container">
                                            <header>
                                                <h2 class="title post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                                                <?php if($mts_options['mts_home_headline_meta'] == '1') { ?>
                                                    <div class="post-info">
                                                        <?php if(isset($mts_options['mts_home_headline_meta_info']['date']) == '1') { ?>
                                                            <span class="thetime updated"><?php the_time( get_option( 'date_format' ) ); ?></span>
                                                        <?php } ?>
                                                        <?php if (function_exists('wp_review_show_total')) wp_review_show_total(true, 'review-total-only'); ?>
                                                    </div>
                                                <?php } ?>
                                            </header>
                                            <div class="post-excerpt">
                                                <?php echo mts_excerpt(15); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="post-day"><?php the_time( 'j' ); ?></div>
                            </li>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </ul>
                    </div>
                </div>
            </div><!-- slider-container -->
            <div class="static-posts">
                <?php
                $sp_query = new WP_Query('cat='.$slider_cat.'&posts_per_page=2&offset='.$mts_options['mts_featured_slider_num']);
                $sp_count = 1; while ($sp_query->have_posts()) : $sp_query->the_post();
                ?>
                <article class="post-box latestPost vertical-small dark-style clear-none <?php if ( 1 == $sp_count ) echo'image-bottom'; ?>">
                    <?php if ( 1 == $sp_count ) { ?>
                        <div class="post-data">
                            <div class="post-data-container">
                                <header>
                                    <h2 class="title post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                                    <?php if($mts_options['mts_home_headline_meta'] == '1') { ?>
                                        <div class="post-info">
                                            <?php if(isset($mts_options['mts_home_headline_meta_info']['date']) == '1') { ?>
                                                <span class="thetime updated"><?php the_time( get_option( 'date_format' ) ); ?></span>
                                            <?php } ?>
                                            <?php if (function_exists('wp_review_show_total')) wp_review_show_total(true, 'review-total-only'); ?>
                                        </div>
                                    <?php } ?>
                                </header>
                                <div class="post-excerpt">
                                    <?php echo mts_excerpt(15); ?>
                                </div>
                            </div>
                        </div><!--.post-data-->
                    <?php } ?>
                    <div class="post-img">
                        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="nofollow">
                            <?php the_post_thumbnail('featured3',array('title' => '')); ?>
                            <div class="post-day"><?php the_time( 'j' ); ?></div>
                        </a>
                    </div><!--.post-img-->
                    <?php if ( 2 == $sp_count ) { ?>
                    <div class="post-data">
                        <div class="post-data-container">
                            <header>
                                <h2 class="title post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                                <?php if($mts_options['mts_home_headline_meta'] == '1') { ?>
                                    <div class="post-info">
                                        <?php if(isset($mts_options['mts_home_headline_meta_info']['date']) == '1') { ?>
                                            <span class="thetime updated"><?php the_time( get_option( 'date_format' ) ); ?></span>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </header>
                            <div class="post-excerpt">
                                <?php echo mts_excerpt(15); ?>
                            </div>
                        </div>
                    </div><!--.post-data-->
                    <?php } ?>
                </article><!--.post-box-->
            <?php $sp_count++; endwhile; wp_reset_postdata(); ?>
            </div><!--.static-posts-->
        </section><!--#featured-section-1-->
        <?php } ?>
        <?php if ( '1' === $homepage_fs_layout && $f1_enabled ) { ?>
        <section id="featured-section-2" class="featured-section clearfix featured-section-2-1">
            <?php if (!empty($f1_title)) { ?><h4 class="featured-section-title"><?php echo $f1_title;?></h4><?php }?>
            <?php
            
            $f1_query = new WP_Query('cat='.$f1_cat.'&posts_per_page='.$f1_post_num);
            $f1_count = 1; if ($f1_query->have_posts()) :

            $f1_cats = get_categories( array('include'=> $f1_cat ) );

            mts_f1_ajax_nav( $f1_cats, $f1_filter, $f1_pagination );
            ?>
            <div class="fs1-posts">
                <input type="hidden" class="page_num" name="page_num" value="1" />
                <input type="hidden" class="max_pages" name="max_pages" value="<?php echo $f1_query->max_num_pages; ?>" />
                <?php
                while ($f1_query->have_posts()) : $f1_query->the_post(); ?>
                <article class="post-box latestPost vertical-small dark-style clear-none <?php if ( $f1_count % 3 === 2 ) echo'image-bottom'; ?>">
                    <?php if ( $f1_count % 3 === 2) { ?>
                        <div class="post-data">
                            <div class="post-data-container">
                                <header>
                                    <h2 class="title post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                                    <?php if($mts_options['mts_home_headline_meta'] == '1') { ?>
                                        <div class="post-info">
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
                                    <?php echo mts_excerpt(15); ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="post-img">
                        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="nofollow">
                            <?php the_post_thumbnail('featured',array('title' => '')); ?>
                        </a>
                    </div>
                    <?php if ( $f1_count % 3 !== 2 ) { ?>
                    <div class="post-data">
                        <div class="post-data-container">
                            <header>
                                <h2 class="title post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                                <?php if($mts_options['mts_home_headline_meta'] == '1') { ?>
                                    <div class="post-info">
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
                                <?php echo mts_excerpt(15); ?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </article><!--.post-box-->
            <?php $f1_count++; endwhile; endif; wp_reset_postdata(); ?>
            </div>
        </section><!--#featured-section-2-->
        <?php } ?>
    <?php } ?>
    <div class="article">
        <div id="content_box">
            <?php if (is_home() && !is_paged() ) { ?>
                <?php if( '1' !== $homepage_fs_layout && $f1_enabled ) { ?>
                <section id="featured-section-2" class="featured-section clearfix featured-section-2-1">
                    <?php if (!empty($f1_title)) { ?><h4 class="featured-section-title"><?php echo $f1_title;?></h4><?php }?>
                    <?php
                    $f1_query = new WP_Query('cat='.$f1_cat.'&posts_per_page='.$f1_post_num);
                    $f1_count = 1; if ($f1_query->have_posts()) :
                    $f1_cats = get_categories( array('include'=> $f1_cat ) );

                    mts_f1_ajax_nav( $f1_cats, $f1_filter, $f1_pagination );
                    ?>
                    <div class="fs1-posts">
                        <input type="hidden" class="page_num" name="page_num" value="1" />
                        <input type="hidden" class="max_pages" name="max_pages" value="<?php echo $f1_query->max_num_pages; ?>" />
                    <?php
                    while ($f1_query->have_posts()) : $f1_query->the_post(); ?>
                        <article class="post-box latestPost vertical-small dark-style clear-none <?php if ( $f1_count % 3 === 2 ) echo'image-bottom'; ?>">
                            <?php if ( $f1_count % 3 === 2 ) { ?>
                                <div class="post-data">
                                    <div class="post-data-container">
                                        <header>
                                            <h2 class="title post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                                            <?php if($mts_options['mts_home_headline_meta'] == '1') { ?>
                                                <div class="post-info">
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
                                            <?php echo mts_excerpt(15); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="post-img">
                                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="nofollow">
                                    <?php the_post_thumbnail('featured2',array('title' => '')); ?>
                                </a>
                            </div>
                            <?php if ( $f1_count % 3 !== 2 ) { ?>
                            <div class="post-data">
                                <div class="post-data-container">
                                    <header>
                                        <h2 class="title post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
                                        <?php if($mts_options['mts_home_headline_meta'] == '1') { ?>
                                            <div class="post-info">
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
                                        <?php echo mts_excerpt(15); ?>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </article><!--.post-box-->
                    <?php $f1_count++; endwhile; endif; wp_reset_postdata(); ?>
                    </div>
                </section><!--#featured-section-2-->
                <?php } ?>
            <?php } ?>

            <?php if ( ! is_paged() ) {

                $latest_posts_used = false;
                if ( !empty( $mts_options['mts_featured_categories'] ) ) {
                    foreach ( $mts_options['mts_featured_categories'] as $section ) {
                        $category_id = $section['mts_featured_category'];
                        $featured_category_layout = $section['mts_featured_category_layout'];
                        $posts_num = $section['mts_featured_category_postsnum'];
                        if ( $category_id === 'latest' && ! $latest_posts_used ) {
                            $latest_posts_used = true;
                            $fc_section_class  = ( in_array( $featured_category_layout, array( 'horizontal', 'vertical' ) ) ) ? '' : ' '.$featured_category_layout;
                            $fc_section_no_gap = ( $featured_category_layout === 'dark' ) ? ' no-gap' : '';
                            ?>
                            <section id="latest-posts" class="clearfix<?php echo $fc_section_class ?><?php echo $fc_section_no_gap ?>">
                                <h4 class="featured-section-title"><?php _e( "Latest Articles", "mythemeshop" ); ?></h4>
                                <?php $j = 1; if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                                <?php newstimes_the_homepage_article( $featured_category_layout, $j, true );?>
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
                        <?php } elseif ( $category_id !== 'latest' ) {

                            $fc_section_class  = ( in_array( $featured_category_layout, array( 'horizontal', 'vertical' ) ) ) ? '' : ' '.$featured_category_layout;

                            ?>
                            <section class="featured-section clearfix<?php echo $fc_section_class ?>">
                                <h4 class="featured-section-title"><a href="<?php echo esc_url( get_category_link($category_id) ); ?>" title="<?php echo esc_attr( get_cat_name($category_id) ); ?>"><?php echo get_cat_name($category_id); ?></a></h4>
                                <?php $cat_query = new WP_Query('cat='.$category_id.'&posts_per_page='.$posts_num); ?>
                                <?php $j = 1; if ($cat_query->have_posts()) : while ($cat_query->have_posts()) : $cat_query->the_post(); ?>
                                <?php newstimes_the_homepage_article( $featured_category_layout, $j );?>
                                <?php $j++; endwhile; endif; wp_reset_postdata();?>
                            </section>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            <?php } else {

                // Paged

                $latest_section_layout = 'vertical'; // default layout if latest posts section does not exists
                if ( !empty( $mts_options['mts_featured_categories'] ) ) {
                    foreach ( $mts_options['mts_featured_categories'] as $section ) {
                        if ( $section['mts_featured_category'] === 'latest' ) {
                            $latest_section_layout = $section['mts_featured_category_layout'];
                            break;
                        }
                    }
                }

                $fc_section_class  = ( in_array( $latest_section_layout, array( 'horizontal', 'vertical', 'mixed' ) ) ) ? '' : ' '.$latest_section_layout;
                $fc_section_no_gap = ( $latest_section_layout === 'dark' ) ? ' no-gap' : '';
                ?>
                <section id="latest-posts" class="clearfix<?php echo $fc_section_class ?><?php echo $fc_section_no_gap ?>">
                <?php $j = 1; if (have_posts()) : while (have_posts()) : the_post(); ?>
                <?php newstimes_the_homepage_article( $latest_section_layout, $j, true );?>
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
            <?php } ?>
        </div>
    </div>
    <?php get_sidebar(); ?>
<?php get_footer(); ?>