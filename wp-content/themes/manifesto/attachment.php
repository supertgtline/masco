<?php
$dateformat = get_option('date_format');
$timeformat = get_option('time_format');
?>

<?php get_header(); ?>

<?php
// post custom fields 
$template = get_post_meta($post->ID, 'wpzoom_post_template', true);
$showsocial = get_post_meta($post->ID, 'wpzoom_post_social', true);
$showauthor = get_post_meta($post->ID, 'wpzoom_post_author', true); 
?>


  <div id="frame">
  <div id="content">
  
  
    <div class="wrapper">
    
    <div id="main">
      
      <?php wp_reset_query(); if (have_posts()) : while (have_posts()) : the_post(); ?>
      
        <div id="single">
        
        <div class="title breadcrumbs">
           <h3><a href="<?php echo get_permalink($post->post_parent); ?>" rev="attachment"><?php echo get_the_title($post->post_parent); ?></a> &raquo; <?php the_title(); ?></h3>
        </div><!-- end .title -->
        
        <div class="box box-single">
            
            <div class="postcontent">
            
        <a href="<?php echo wp_get_attachment_url($post->ID); ?>"><?php echo wp_get_attachment_image( $post->ID, 'medium' ); ?></a>
				<div class="caption"><?php if ( !empty($post->post_excerpt) ) the_excerpt(); // this is the "caption" ?></div>
            
            </div>
            
            <div class="cleaner">&nbsp;</div>
          
        </div><!-- end .box -->
            
          </div><!-- end #single -->
        
        <div class="cleaner">&nbsp;</div>
        
   <?php endwhile; else: ?>

		<p><?php _e('Sorry, no posts matched your criteria.', 'wpzoom');?></p>
    <?php endif; ?>
        
      </div><!-- end #main -->
      
      <?php if ($template != 'Full Width (no sidebar)') { ?>
      <div id="sidebar">
          
            <?php get_sidebar(); ?>
            
          </div><!-- end #sidebar -->
        <?php } //if template is not full width  ?>
 
      <div class="cleaner">&nbsp;</div>
    </div><!-- end .wrapper -->
  </div><!-- end #content -->
  </div><!-- end #frame -->

<?php get_footer(); ?>