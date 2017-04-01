<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title><?php ui::title(); ?></title>

	<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" media="screen" />

    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

    <?php wp_head(); ?>
    
    <?php ui::js("loopedslider.min"); ?>

	<!--[if IE 7.0]>
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/style_ie7.css" />
	<![endif]-->

</head>
<body <?php body_class() ?>>

<div id="container">

	<div id="header">

		<div id="logo">
			<a href="<?php echo home_url('/'); ?>"><img src="<?php echo ui::logo(); ?>" alt="<?php bloginfo('name'); ?>" /></a>
		</div><!-- end #logo -->

		<?php if (option::get('banner_header_enable') == 'on') { ?>
		<div class="header-banner">
				
			<?php if ( option::get('banner_header_html') <> "") { 
				echo stripslashes(option::get('banner_header_html'));             
			} else { ?>
				<a href="<?php echo option::get('banner_header_url'); ?>" rel="nofollow" title="<?php echo option::get('banner_header_alt'); ?>"><img src="<?php echo option::get('banner_header'); ?>" alt="<?php echo option::get('banner_header_alt'); ?>" /></a>
			<?php } ?>		   	
						
		</div><!-- end .header-banner -->

		<div class="cleaner">&nbsp;</div>
		<?php } ?>
      
    	<div class="cleaner">&nbsp;</div>

	</div><!-- end #header -->
  
	<div id="navigation">
		<img src="<?php bloginfo('template_url'); ?>/images/men_crn_left.png" width="2" height="29" alt="" class="alignleft" />
		<img src="<?php bloginfo('template_url'); ?>/images/men_crn_right.png" width="2" height="29" alt="" class="alignright" />
		<?php if (option::get('social_icons_show') == 'on') { ?>
		<div id="menuSocial">
			<ul>
				<?php if (option::get('social_icons_facebook')) { ?><li><a href="<?php echo option::get('social_icons_facebook'); ?>" rel="external,nofollow"><img src="<?php bloginfo('template_url'); ?>/images/icons/ic_facebook.png" width="16" height="16" alt="Facebook Icon" /></a></li><?php } ?>
				<?php if (option::get('social_icons_twitter')) { ?><li><a href="<?php echo option::get('social_icons_twitter'); ?>" rel="external,nofollow"><img src="<?php bloginfo('template_url'); ?>/images/icons/ic_twitter.png" width="16" height="16" alt="Twitter Icon" /></a></li><?php } ?>
				<li><a href="<?php ui::rss(); ?>"><img src="<?php bloginfo('template_url'); ?>/images/icons/ic_rss.png" width="16" height="16" alt="RSS Icon" /></a></li>
			</ul>
		</div><!-- end #menuSocial -->
		<?php } ?>
		
		<div id="menu" class="dropdown">
			<?php 
			$homeLink = '<a href="' . get_option('home') . ' "rel="nofollow"><img src="' . get_bloginfo('template_url') .'/images/men_icon_home.png" width="16" height="14" alt="" /></a>';
			$menu = wp_nav_menu(array(
				'container' => '',
				'container_class' => '',
				'menu_class' => 'home',
				'menu_id' => 'nav',
				'echo' => false,
				'sort_column' =>'menu_order',
				'theme_location' =>'primary',
				'items_wrap'=>'<ul id="%s"><li class="%s">'.$homeLink.'</li>%s<li class="cleaner">&nbsp;</li></ul>'
			));
			print $menu;
			?>
		</div>
	
	</div><!-- end #navigation -->