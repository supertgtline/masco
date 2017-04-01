<?php
$dateformat = get_option('date_format');
$timeformat = get_option('time_format');
?>

<?php get_header(); ?>

<div id="frame">
	<div id="content">
		
		<div class="wrapper">
		
				<?php if ($paged < 2) { 

					if (option::get('featured_enable') == 'on' && is_home()) { get_template_part('featured-posts'); }

					if (option::get('featured_cats_show') == 'on' && is_home()) { get_template_part('featured-categories'); }

				} // if $paged < 2 ?>
			
			<div id="main">
			
				<?php if ($paged < 2) {  
					
					if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Homepage: Content Widgets') ) : ?> <?php endif; ?>
				
				<?php } ?>
				
				<?php if (option::get('recent_part_enable') == 'on') { get_template_part('loop', 'index'); } ?>
			
			</div><!-- end #main -->
			
			<div id="sidebar">
			
				<?php get_sidebar(); ?>
			
			</div><!-- end #sidebar -->
			
			<div class="cleaner">&nbsp;</div>
		</div><!-- end .wrapper -->
	</div><!-- end #content -->
</div><!-- end #frame -->

<?php get_footer(); ?>
