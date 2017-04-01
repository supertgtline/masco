<?php if (option::get('banner_sidebar_top_enable') == 'on') { ?>
<div class="widget side_ad">
		
	<?php if ( option::get('banner_sidebar_top_html') <> "") { 
		echo stripslashes(option::get('banner_sidebar_top_html'));             
	} else { ?>
		<a href="<?php echo option::get('banner_sidebar_top_url'); ?>" rel="nofollow" title="<?php echo option::get('banner_sidebar_top_alt'); ?>"><img src="<?php echo option::get('banner_sidebar_top'); ?>" alt="<?php echo option::get('banner_sidebar_top_alt'); ?>" /></a>
	<?php } ?>		   	
				
</div><!-- end .widget .side_ad -->
<?php } ?>

<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar') ) : ?> <?php endif; ?>

<?php if (option::get('banner_sidebar_bottom_enable') == 'on') { ?>
<div class="widget side_ad">
		
	<?php if ( option::get('banner_sidebar_bottom_html') <> "") { 
		echo stripslashes(option::get('banner_sidebar_bottom_html'));             
	} else { ?>
		<a href="<?php echo option::get('banner_sidebar_bottom_url'); ?>" rel="nofollow" title="<?php echo option::get('banner_sidebar_bottom_alt'); ?>"><img src="<?php echo option::get('banner_sidebar_bottom'); ?>" alt="<?php echo option::get('banner_sidebar_bottom_alt'); ?>" /></a>
	<?php } ?>		   	
				
</div><!-- end .widget .side_ad -->
<?php } ?>

<div class="cleaner">&nbsp;</div>