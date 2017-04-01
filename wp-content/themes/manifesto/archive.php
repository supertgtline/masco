<?php get_header(); ?>

<div id="frame">
	<div id="content">
	
		<div class="wrapper">
		
			<div id="main">
			
				<?php get_template_part('loop'); ?>
			
			</div><!-- end #main -->
			
			<div id="sidebar">
			
				<?php get_sidebar(); ?>
			
			</div><!-- end #sidebar -->
			
			<div class="cleaner">&nbsp;</div>
		</div><!-- end .wrapper -->
	</div><!-- end #content -->
</div><!-- end #frame -->

<?php get_footer(); ?>