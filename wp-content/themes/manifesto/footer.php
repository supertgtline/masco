	<div id="footnavigation">
	
		<img src="<?php bloginfo('template_url'); ?>/images/men_crn_left_b.png" width="2" height="29" alt="" class="alignleft" />
		<img src="<?php bloginfo('template_url'); ?>/images/men_crn_right_b.png" width="2" height="29" alt="" class="alignright" />
		
		<?php    $homeLink = '<a href="' . get_option('home') . '"rel="nofollow"><img src="' . get_bloginfo('template_url') .'/images/men_icon_home.png" width="16" height="14" alt="" /></a>';
		$menu = wp_nav_menu(array(
			'container' => '',
			'container_class' => '',
			'menu_class' => 'home',
			'menu_id' => 'footnav',
			'echo' => false,
			'depth' => '1',
			'sort_column' =>'menu_order',
			'theme_location' =>'secondary',
			'items_wrap'=>'<ul id="%s"><li class="%s">'.$homeLink.'</li>%s<li class="cleaner">&nbsp;</li></ul>'
		));
		print $menu;
		?>
	
	</div><!-- end #footnavigation -->
	
	<div id="footer">
		<p class="wpzoom"><a href="http://www.wpzoom.com" target="_blank"><?php _e('Magazine WordPress Theme', 'wpzoom'); ?></a> <?php _e('by', 'wpzoom'); ?> <a href="http://www.wpzoom.com" target="_blank" title="Magazine WordPress Themes">
		<img src="<?php bloginfo('template_url'); ?>/styles/<?php echo strtolower(option::get('theme_style')); ?>/images/wpzoom.png" alt="WPZOOM" width="72" height="16" /></a></p>
		<p class="copy"><?php _e('Copyright', 'wpzoom'); ?> &copy; <?php echo date("Y",time()); ?> <?php bloginfo('name'); ?>. <?php _e('All Rights Reserved', 'wpzoom'); ?>.</p>
	</div><!-- end #footer -->
    
</div><!-- end #container -->

<?php wp_footer(); ?>

<?php if (is_single() || is_page()) { ?>
	<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
<?php } ?>

</body>
</html>
