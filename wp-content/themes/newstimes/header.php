<!DOCTYPE html>
<?php $mts_options = get_option(newstimes); ?>
<html class="no-js" <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
	<!--[if IE ]>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<![endif]-->
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<?php mts_meta(); ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head(); ?>
</head>
<body id ="blog" <?php body_class('main'); ?> itemscope itemtype="http://schema.org/WebPage">
	<div class="main-container-wrap">
		<header class="main-header">
			<div class="container">
				<div id="header">
					<div class="header-inner">
						<div class="logo-wrap">
							<?php if ($mts_options['mts_logo'] != '') { ?>
								<?php if( is_front_page() || is_home() || is_404() ) { ?>
										<h1 id="logo" class="image-logo">
											<a href="<?php echo home_url(); ?>"><img src="<?php echo $mts_options['mts_logo']; ?>" alt="<?php bloginfo( 'name' ); ?>"></a>
										</h1><!-- END #logo -->
								<?php } else { ?>
										<h2 id="logo" class="image-logo">
											<a href="<?php echo home_url(); ?>"><img src="<?php echo $mts_options['mts_logo']; ?>" alt="<?php bloginfo( 'name' ); ?>"></a>
										</h2><!-- END #logo -->
								<?php } ?>
							<?php } else { ?>
								<?php if( is_front_page() || is_home() || is_404() ) { ?>
										<h1 id="logo" class="text-logo">
											<a href="<?php echo home_url(); ?>"><?php bloginfo( 'name' ); ?></a>
										</h1><!-- END #logo -->
								<?php } else { ?>
										<h2 id="logo" class="text-logo">
											<a href="<?php echo home_url(); ?>"><?php bloginfo( 'name' ); ?></a>
										</h2><!-- END #logo -->
								<?php } ?>
								<div class="site-description">
									<?php bloginfo( 'description' ); ?>
								</div>
							<?php } ?>
						</div>
						<?php dynamic_sidebar('Header Ad'); ?>
					</div>
					<?php if ($mts_options['mts_show_secondary_nav'] == '1'): ?>
					<?php if($mts_options['mts_sticky_nav'] == '1') { ?>
						<div class="clear" id="catcher"></div>
						<div id="sticky" class="secondary-navigation">
					<?php } else { ?>
					<div class="secondary-navigation">
					<?php } ?>
						<nav id="navigation" class="clearfix">
							<a href="#" class="toggle-mobile-menu"><?php _e('Menu','mythemeshop'); ?></a>
							<?php if ( has_nav_menu( 'secondary-menu' ) ) { ?>
								<?php wp_nav_menu( array( 'theme_location' => 'secondary-menu', 'menu_class' => 'menu clearfix', 'container' => '', 'walker' => new mts_menu_walker ) ); ?>
							<?php } else { ?>
								<ul class="menu clearfix">
									<?php wp_list_categories('title_li='); ?>
								</ul>
							<?php } ?>
						</nav>
						<?php if ( $mts_options['mts_header_search_form'] === '1' ) { ?>
							<div class="header-search">
								<a href="#" class="fa fa-search"></a>
								<form class="search-form" action="<?php echo home_url(); ?>" method="get">
									<input class="hideinput" name="s" id="s" type="search" placeholder="<?php _e('Search...', 'mythemeshop'); ?>" autocomplete="off" x-webkit-speech="x-webkit-speech" />
								</form>
							</div>
						<?php } ?>
					</div>
					<?php endif; ?>
					<?php if ( is_home() && !is_paged() && ( $mts_options['mts_homepage_fs_layout'] === '1' || $mts_options['mts_homepage_fs_layout'] === '2' ) && $mts_options['mts_featured_slider'] === '1' ) { ?>
					<div id="header-bottom-spacer"></div>
					<?php } ?>
				</div><!--#header-->
			</div><!--.container-->
		</header>
		<div class="main-container">