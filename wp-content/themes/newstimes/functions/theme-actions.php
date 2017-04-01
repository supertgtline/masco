<?php
$mts_options = get_option(newstimes);
/*------------[ Meta ]-------------*/
if ( ! function_exists( 'mts_meta' ) ) {
	function mts_meta(){
	global $mts_options;
?>
<?php if ($mts_options['mts_favicon'] != ''){ ?>
	<link rel="icon" href="<?php echo $mts_options['mts_favicon']; ?>" type="image/x-icon" />
<?php } ?>
<!--iOS/android/handheld specific -->
<link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/apple-touch-icon.png" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<?php if($mts_options['mts_prefetching'] == '1') { ?>
<?php if (is_front_page()) { ?>
	<?php $my_query = new WP_Query('posts_per_page=1'); while ($my_query->have_posts()) : $my_query->the_post(); ?>
	<link rel="prefetch" href="<?php the_permalink(); ?>">
	<link rel="prerender" href="<?php the_permalink(); ?>">
	<?php endwhile; wp_reset_query(); ?>
<?php } elseif (is_singular()) { ?>
	<link rel="prefetch" href="<?php echo home_url(); ?>">
	<link rel="prerender" href="<?php echo home_url(); ?>">
<?php } ?>
<?php } ?>
<?php }
}

/*------------[ Head ]-------------*/
if ( ! function_exists( 'mts_head' ) ){
	function mts_head() {
	global $mts_options;
?>
<?php echo $mts_options['mts_header_code']; ?>
<?php }
}
add_action('wp_head', 'mts_head');

/*------------[ Copyrights ]-------------*/
if ( ! function_exists( 'mts_copyrights_credit' ) ) {
	function mts_copyrights_credit() { 
	global $mts_options;
?>
<!--start copyrights-->
<div id="copyright-note">
<span><a href="<?php echo home_url(); ?>/" title="<?php bloginfo('description'); ?>" rel="nofollow"><?php bloginfo('name'); ?></a> Copyright &copy; <?php echo date("Y") ?>.</span>
<div class="right"><?php echo $mts_options['mts_copyrights']; ?></div>
</div>
<!--end copyrights-->
<?php }
}

/*------------[ footer ]-------------*/
if ( ! function_exists( 'mts_footer' ) ) {
	function mts_footer() { 
	global $mts_options;
?>
<?php if ($mts_options['mts_analytics_code'] != '') { ?>
<!--start footer code-->
<?php echo $mts_options['mts_analytics_code']; ?>
<!--end footer code-->
<?php } ?>
<?php }
}

/*------------[ breadcrumb ]-------------*/
if (!function_exists('mts_the_breadcrumb')) {
	function mts_the_breadcrumb() {
		echo '<span typeof="v:Breadcrumb" class="root"><a rel="v:url" property="v:title" href="';
		echo home_url();
		echo '" rel="nofollow"><i class="fa fa-home"></i>';
		echo "</a></span>";
		if (is_category() || is_single()) {
			$categories = get_the_category();
			$output = '';
			if($categories){
				foreach($categories as $category) {
					echo '<div typeof="v:Breadcrumb"><a href="'.get_category_link( $category->term_id ).'" rel="v:url" property="v:title">'.$category->cat_name.'</a></div><div><i class="fa fa-caret-right"></i></div>';
				}
			}
			if (is_single()) {
				echo "<div typeof='v:Breadcrumb'><span property='v:title'>";
				the_title();
				echo "</span></div>";
			}
		} elseif (is_page()) {
			echo "<div typeof='v:Breadcrumb'><span property='v:title'>";
			the_title();
			echo "</span></div>";
		}
	}
}

/*------------[ schema.org-enabled the_category() and the_tags() ]-------------*/
function mts_the_category( $separator = ', ' ) {
    $categories = get_the_category();
    $count = count($categories);
    foreach ( $categories as $i => $category ) {
        echo '<a href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s", 'mythemeshop' ), $category->name ) . '" ' . ' itemprop="articleSection">' . $category->name.'</a>';
        if ( $i < $count - 1 )
            echo $separator;
    }
}
function mts_the_tags($before = null, $sep = ', ', $after = '') {
    if ( null === $before ) 
        $before = __('Tags: ', 'mythemeshop');
    
    $tags = get_the_tags();
    if (empty( $tags ) || is_wp_error( $tags ) ) {
        return;
    }
    $tag_links = array();
    foreach ($tags as $tag) {
        $link = get_tag_link($tag->term_id);
        $tag_links[] = '<a href="' . esc_url( $link ) . '" rel="tag" itemprop="keywords">' . $tag->name . '</a>';
    }
    echo $before.join($sep, $tag_links).$after;
}

/*------------[ pagination ]-------------*/
if (!function_exists('mts_pagination')) {
    function mts_pagination($pages = '', $range = 3) { 
		$showitems = ($range * 3)+1;
		global $paged; if(empty($paged)) $paged = 1;
		if($pages == '') {
			global $wp_query; $pages = $wp_query->max_num_pages; 
			if(!$pages){ $pages = 1; } 
		}
		if(1 != $pages) { 
			echo "<div class='pagination'><ul>";
			if($paged > 2 && $paged > $range+1 && $showitems < $pages) 
				echo "<li><a rel='nofollow' href='".get_pagenum_link(1)."'><i class='fa fa-chevron-left'></i> ".__('First','mythemeshop')."</a></li>";
			if($paged > 1 && $showitems < $pages) 
				echo "<li><a rel='nofollow' href='".get_pagenum_link($paged - 1)."' class='inactive'>&lsaquo; ".__('Previous','mythemeshop')."</a></li>";
			for ($i=1; $i <= $pages; $i++){ 
				if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )) { 
					echo ($paged == $i)? "<li class='current'><span class='currenttext'>".$i."</span></li>":"<li><a rel='nofollow' href='".get_pagenum_link($i)."' class='inactive'>".$i."</a></li>";
				} 
			} 
			if ($paged < $pages && $showitems < $pages) 
				echo "<li><a rel='nofollow' href='".get_pagenum_link($paged + 1)."' class='inactive'>".__('Next','mythemeshop')." &rsaquo;</a></li>";
			if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) 
				echo "<li><a rel='nofollow' class='inactive' href='".get_pagenum_link($pages)."'>".__('Last','mythemeshop')." &raquo;</a></li>";
				echo "</ul></div>"; 
		}
	}
}

/*------------[ Cart ]-------------*/
if ( ! function_exists( 'mts_cart' ) ) {
	function mts_cart() { 
	   if (mts_isWooCommerce()) {
	   global $mts_options;
?>
<div class="mts-cart">
	<?php global $woocommerce; ?>
	<span>
		<i class="fa fa-user"></i> 
		<?php if ( is_user_logged_in() ) { ?>
			<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('My Account','mythemeshop'); ?>"><?php _e('My Account','mythemeshop'); ?></a>
		<?php } 
		else { ?>
			<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('Login / Register','mythemeshop'); ?>"><?php _e('Login ','mythemeshop'); ?></a>
		<?php } ?>
	</span>
	<span>
		<i class="fa fa-shopping-cart"></i> <a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php _e('View your shopping cart', 'mythemeshop'); ?>"><?php echo sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'mythemeshop'), $woocommerce->cart->cart_contents_count);?> - <?php echo $woocommerce->cart->get_cart_total(); ?></a>
	</span>
</div>
<?php } 
    }
}

/*------------[ Homepage articles  ]-------------*/
function newstimes_the_homepage_article( $layout, $j, $latest = false ) {

	global $mts_options;

	$extra_article_class = ( $latest ) ? ' latestPost' : '';// needed for ajax load more

	switch ( $layout ) {
		case 'horizontal':
		case 'vertical':

		// Thumbnail
		$fc_post_loop_thumb  = ('horizontal' == $layout) ? 'featured' : 'featuredfull';
		// Other defaults
		$fc_show_author = true;
		$fc_show_excerpt = true;
		$fc_begin_extra_wrappers = '';
		$fc_end_extra_wrappers ='';
		$fc_clear_class = '';
		if ( $layout === 'horizontal' ) {

			$fc_begin_extra_wrappers = '<div class="horizontal-container"><div class="horizontal-container-inner">';
			$fc_end_extra_wrappers = '</div></div>';
		}
	?>
		<article class="latestPost<?php echo $extra_article_class;?> post-box <?php echo $layout;?> <?php echo $fc_clear_class;?>">
			<?php echo $fc_begin_extra_wrappers; ?>
			<div class="post-img">
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="nofollow">
					<?php the_post_thumbnail( $fc_post_loop_thumb, array( 'title' => '' ) ); ?>
				</a>
			</div>
			<div class="post-data">
				<div class="post-data-container">
					<header>
						<h2 class="title post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
						<?php if($mts_options['mts_home_headline_meta'] == '1') { ?>
							<div class="post-info">
								<?php if( isset($mts_options['mts_home_headline_meta_info']['category']) == '1' && 'vertical' == $layout ) { ?>
									<span class="thecategory"><?php the_category(' ') ?></span>
								<?php } ?>
								<?php if(isset($mts_options['mts_home_headline_meta_info']['date']) == '1') { ?>
									<span class="thetime updated"><?php the_time( get_option( 'date_format' ) ); ?></span>
								<?php } ?>
								<?php if(isset($mts_options['mts_home_headline_meta_info']['comment']) == '1') { ?>
									<span class="thecomment"><i class="fa fa-comments"></i> <a rel="nofollow" href="<?php comments_link(); ?>"><?php echo comments_number('0','1','%');?></a></span>
								<?php } ?>
								<?php if(isset($mts_options['mts_home_headline_meta_info']['author']) == '1' && $fc_show_author ) { ?>
									<span class="theauthor"><i class="fa fa-user"></i> <?php the_author_posts_link(); ?></span>
								<?php } ?>
								<?php if (function_exists('wp_review_show_total')) wp_review_show_total(true, 'review-total-only'); ?>
							</div>
						<?php } ?>
					</header>
					<?php if ($fc_show_excerpt): ?>
					<div class="post-excerpt">
						<?php if ('vertical' == $layout) : ?>
							<?php echo mts_excerpt(35); ?>
						<?php elseif ('horizontal' == $layout): ?>
							<?php echo mts_excerpt(15); ?>
						<?php else: ?>
							<?php echo mts_excerpt(25); ?>
						<?php endif; ?>
					</div>
					<?php endif; ?>
					<?php if ('vertical' == $layout) : ?>
						<?php mts_readmore(); ?>
					<?php endif; ?>
				</div>
			</div>
			<?php echo $fc_end_extra_wrappers; ?>
		</article><!--.post-box-->
	<?php break;
	}
}

?>